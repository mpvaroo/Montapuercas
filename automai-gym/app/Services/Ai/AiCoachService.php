<?php

namespace App\Services\Ai;

use App\Models\ChatConversacion;
use App\Models\ChatMensajeIA;
use App\Models\ReservaClase;
use App\Models\User;
use App\Mail\ReservationConfirmed;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class AiCoachService
{
    private OllamaClient $ollama;

    private static array $DIAS_ES = [
        'Monday'=>'lunes','Tuesday'=>'martes','Wednesday'=>'miércoles',
        'Thursday'=>'jueves','Friday'=>'viernes','Saturday'=>'sábado','Sunday'=>'domingo',
    ];

    public function __construct()
    {
        $this->ollama = new OllamaClient();
    }

    public function handleMessage(User $user, int $conversationId, string $userMessage): array
    {
        ChatMensajeIA::create([
            'id_conversacion' => $conversationId,
            'id_usuario'      => $user->id_usuario,
            'rol'             => 'user',
            'contenido'       => $userMessage,
        ]);

        $perfil  = DB::table('perfiles_usuario')->where('id_usuario', $user->id_usuario)->first();
        $ajustes = DB::table('ajustes_usuario')->where('id_usuario', $user->id_usuario)->first();
        $systemPrompt = $this->buildSystemPrompt($user, $perfil, $ajustes);

        // Solo los últimos 16 mensajes para no saturar el contexto del LLM
        $historial = ChatMensajeIA::where('id_conversacion', $conversationId)
            ->orderBy('id', 'desc')
            ->limit(16)
            ->get()->reverse()->values();

        // Contar cuántos mensajes de tipo 'tool' hay para limitar el ruido
        $toolCount = 0;
        $messages = [['role' => 'system', 'content' => $systemPrompt]];
        foreach ($historial as $msg) {
            if ($msg->rol === 'assistant') {
                $messages[] = ['role' => 'assistant', 'content' => $msg->contenido];
            } elseif ($msg->rol === 'tool') {
                // Solo incluir los últimos 3 resultados de herramienta para reducir ruido
                $toolCount++;
                if ($toolCount <= 3) {
                    $toolLabel = strtoupper($msg->tool_name ?? 'TOOL');
                    $messages[] = ['role' => 'user', 'content' => "[RESULTADO {$toolLabel}]: {$msg->contenido}"];
                }
            } else {
                $messages[] = ['role' => 'user', 'content' => $msg->contenido];
            }
        }

        $rawResponse = $this->ollama->chat($messages);
        $parsed      = $this->extractJson($rawResponse);

        if ($parsed === null) {
            $this->saveAssistantMessage($conversationId, $user->id_usuario, $rawResponse);
            return ['message' => $rawResponse, 'action' => 'reply'];
        }

        $action = $parsed['action'] ?? 'reply';

        if ($action === 'reply') {
            $msg = $parsed['message'] ?? '';
            $this->saveAssistantMessage($conversationId, $user->id_usuario, $msg);
            return ['message' => $msg, 'action' => 'reply'];
        }

        // Ejecutar herramienta
        $toolResult = $this->executeTool($action, $parsed['params'] ?? [], $user);

        ChatMensajeIA::create([
            'id_conversacion' => $conversationId,
            'id_usuario'      => $user->id_usuario,
            'rol'             => 'tool',
            'contenido'       => json_encode($toolResult),
            'tool_name'       => $action,
            'tool_payload'    => $parsed['params'] ?? [],
        ]);

        // Usar el mensaje legible determinista (sin segunda llamada al LLM para evitar timeouts)
        $finalMsg = $this->buildHumanReadableResult($action, $toolResult);
        $this->saveAssistantMessage($conversationId, $user->id_usuario, $finalMsg);
        return ['message' => $finalMsg, 'action' => $action, 'tool_result' => $toolResult];
    }

    // ─────────────────────────────────────────────────────────────────────────
    // MENSAJES FALLBACK LEGIBLES (cuando el LLM no se comporta bien)
    // ─────────────────────────────────────────────────────────────────────────

    private function buildHumanReadableResult(string $action, array $result): string
    {
        return match ($action) {
            'create_routine' => isset($result['exito']) && $result['exito']
                ? "✅ ¡Rutina **\"{$result['nombre']}\"** creada con éxito para los **{$result['dia_semana']}** con {$result['ejercicios_creados']} ejercicios. Ya la tienes disponible en tu sección «Mis Rutinas»."
                : "❌ No pude crear la rutina: " . ($result['error'] ?? 'Error desconocido. Inténtalo de nuevo.'),

            'book_class' => isset($result['exito']) && $result['exito']
                ? "✅ ¡Reserva confirmada! Te he apuntado a **\"{$result['clase']}\"** ({$result['inicio']} al {$result['fin']}). La verás en tu sección de Reservas."
                : (isset($result['pasada']) && $result['pasada']
                    ? "❌ No es posible reservar esa clase porque ya ha tenido lugar. Solo puedes reservar clases futuras. ¿Quieres que te muestre las clases disponibles?"
                    : (isset($result['llena']) && $result['llena']
                        ? "❌ Lo siento, esa clase está llena. ¿Quieres que te busque otra clase disponible?"
                        : (isset($result['ya_reservada']) && $result['ya_reservada']
                            ? "⚠️ Ya tienes una reserva activa en esa clase. No se puede hacer una reserva duplicada. ¿Quieres apuntarte a otra clase?"
                            : "❌ No pude hacer la reserva: " . ($result['error'] ?? 'Inténtalo de nuevo.')))),

            'list_classes' => empty($result)
                ? "No hay clases publicadas en este momento."
                : $this->formatClassList($result),

            'progress_summary' => isset($result['sin_datos'])
                ? "Todavía no tienes registros de progreso. Empieza a registrar tu peso y medidas en la sección «Progreso»."
                : $this->formatProgressSummary($result),

            'my_reservations' => empty($result)
                ? "No tienes reservas activas en este momento."
                : $this->formatMyReservations($result),

            'cancel_reservation' => isset($result['exito']) && $result['exito']
                ? "✅ Reserva en **\"{$result['clase']}\"** cancelada correctamente. Ya no estás apuntado a esa clase."
                : "❌ No pude cancelar la reserva: " . ($result['error'] ?? 'Inténtalo de nuevo.'),

            'my_routines' => empty($result)
                ? "No tienes rutinas creadas todavía. ¿Quieres que te cree una personalizada?"
                : $this->formatMyRoutines($result),

            'delete_routine' => isset($result['exito']) && $result['exito']
                ? "✅ La rutina **\"{$result['nombre']}\"** ha sido eliminada correctamente."
                : "❌ No pude eliminar la rutina: " . ($result['error'] ?? 'Inténtalo de nuevo.'),

            'edit_routine' => isset($result['exito']) && $result['exito']
                ? "✅ Rutina **\"{$result['nombre']}\"** actualizada correctamente."
                    . (isset($result['dia_semana']) ? " Día: **{$result['dia_semana']}**." : "")
                : "❌ No pude actualizar la rutina: " . ($result['error'] ?? 'Inténtalo de nuevo.'),

            default => json_encode($result, JSON_UNESCAPED_UNICODE),
        };
    }

    private function formatMyReservations(array $reservas): string
    {
        $dias = ['Monday'=>'lunes','Tuesday'=>'martes','Wednesday'=>'miércoles',
            'Thursday'=>'jueves','Friday'=>'viernes','Saturday'=>'sábado','Sunday'=>'domingo'];
        $meses = [1=>'enero',2=>'febrero',3=>'marzo',4=>'abril',5=>'mayo',6=>'junio',
            7=>'julio',8=>'agosto',9=>'septiembre',10=>'octubre',11=>'noviembre',12=>'diciembre'];
        $activas = [];
        $pasadas = [];
        $canceladas = [];
        foreach ($reservas as $r) {
            $ts = strtotime($r['fecha_inicio']);
            $diaSem = $dias[date('l', $ts)] ?? date('l', $ts);
            $dia  = date('j', $ts);
            $mes  = $meses[(int)date('n', $ts)] ?? '';
            $anio = date('Y', $ts);
            $hora = date('H:i', $ts);
            $fechaLeg = "{$diaSem} {$dia} de {$mes} de {$anio} a las {$hora}";
            $linea = "**{$r['clase']}** — {$fechaLeg}";
            if ($r['estado'] === 'cancelada') {
                $canceladas[] = "🔴 {$linea} *(cancelada)*";
            } elseif ($r['cancelable']) {
                $activas[] = "🟢 {$linea} *(puedes cancelarla)*";
            } else {
                $pasadas[] = "⚫ {$linea} *(finalizada)*";
            }
        }
        $out = ["Aquí están tus reservas:\n"];
        if (!empty($activas)) {
            $out[] = "**Activas:**";
            foreach ($activas as $l) $out[] = $l;
        }
        if (!empty($pasadas)) {
            $out[] = "\n**Pasadas:**";
            foreach ($pasadas as $l) $out[] = $l;
        }
        if (!empty($canceladas)) {
            $out[] = "\n**Canceladas:**";
            foreach ($canceladas as $l) $out[] = $l;
        }
        if (!empty($activas)) {
            $out[] = "\n¿Quieres cancelar alguna? Dime el nombre de la clase.";
        }
        return implode("\n", $out);
    }

    private function formatMyRoutines(array $rutinas): string
    {
        $lineas = ["Tus rutinas:\n"];
        foreach ($rutinas as $r) {
            $dia = $r['dia_semana'] ? ucfirst($r['dia_semana']) : 'Libre';
            $origen = match($r['origen']) {
                'ia_coach'  => '🤖 IA Coach',
                'plantilla' => '📋 Plantilla',
                default     => '👤 Usuario',
            };
            $lineas[] = "• **{$r['nombre']}** — {$dia}, {$r['duracion']} min — {$origen} ({$r['ejercicios']} ejercicios)";
        }
        $lineas[] = "\n¿Quieres eliminar o editar alguna? Dime su nombre.";
        return implode("\n", $lineas);
    }

    private function formatClassList(array $clases): string
    {
        $lineas = ["Estas son las clases disponibles:\n"];
        foreach ($clases as $c) {
            $estado = str_contains($c['plazas'], 'LLENA') ? '🔴 LLENA' : "🟢 {$c['plazas']}";
            $lineas[] = "**{$c['nombre']}** — {$c['fecha']} (instructor: {$c['instructor']}) — {$estado}";
        }
        $lineas[] = "\n¿Quieres reservar alguna? Dime el nombre de la clase.";
        return implode("\n", $lineas);
    }

    private function formatProgressSummary(array $r): string
    {
        $tendencia = match($r['tendencia'] ?? null) {
            'bajando' => '📉 bajando (' . abs($r['diferencia_peso']) . ' kg menos)',
            'subiendo' => '📈 subiendo (' . $r['diferencia_peso'] . ' kg más)',
            default => '➡️ estable',
        };
        return "📊 **Resumen de tu progreso** ({$r['total_registros']} registros):\n"
            . "• Inicio: {$r['inicio']['fecha']} — {$r['inicio']['peso']} kg\n"
            . "• Último: {$r['ultimo']['fecha']} — {$r['ultimo']['peso']} kg\n"
            . "• Tendencia de peso: {$tendencia}";
    }

    // ─────────────────────────────────────────────────────────────────────────
    // SYSTEM PROMPT
    // ─────────────────────────────────────────────────────────────────────────

    private function buildSystemPrompt(User $user, $perfil, $ajustes): string
    {
        $nombre   = $user->nombre_mostrado_usuario ?? 'Usuario';
        $objetivo = $perfil->objetivo_principal_usuario ?? 'no especificado';
        $nivel    = $perfil->nivel_usuario ?? 'no especificado';
        $dias     = $perfil->dias_entrenamiento_semana_usuario ?? '?';
        $peso     = $perfil->peso_kg_usuario ? $perfil->peso_kg_usuario . ' kg' : 'no registrado';
        $altura   = $perfil->altura_cm_usuario ? $perfil->altura_cm_usuario . ' cm' : 'no registrado';
        $tono     = $ajustes->tono_ia_coach ?? 'directo';

        $progresos = DB::table('registros_progreso')
            ->where('id_usuario', $user->id_usuario)
            ->orderBy('fecha_registro', 'desc')->limit(3)->get();

        $progresoStr = 'Sin registros todavía.';
        if ($progresos->isNotEmpty()) {
            $progresoStr = $progresos->map(fn ($r) =>
                "{$r->fecha_registro}: {$r->peso_kg_registro} kg"
            )->implode(' | ');
        }

        $tonoDesc  = $tono === 'motivador' ? 'Usa un tono motivador y entusiasta.' : 'Usa un tono directo y profesional.';
        $fechaHoy  = now()->locale('es')->isoFormat('dddd D [de] MMMM [de] YYYY [—] HH:mm');

        // IMPORTANTE: usar variables para los ejemplos de JSON para evitar problemas con llaves en heredoc
        $jsonReply     = '{"action":"reply","message":"texto en espanol"}';
        $jsonTool      = '{"action":"NOMBRE","params":{...}}';
        $jsonBookClass = '{"id_clase":1}';

        return <<<PROMPT
Eres IA Coach, el entrenador personal inteligente de AutomAI Gym. {$tonoDesc} Responde siempre en espanol.

PERFIL DEL USUARIO ({$nombre}):
- Objetivo: {$objetivo} | Nivel: {$nivel} | Dias/semana: {$dias}
- Peso: {$peso} | Altura: {$altura}
- Progreso reciente: {$progresoStr}
- Fecha y hora actual: {$fechaHoy}

HERRAMIENTAS DISPONIBLES - activarlas cuando el usuario lo pida:
* progress_summary -> analiza progreso del usuario
* create_routine -> crea rutina (solo tras recopilar todos los datos)
* list_classes -> lista SOLO clases FUTURAS del gimnasio (las pasadas no se muestran)
* book_class -> reserva una clase por nombre (usa nombre_clase exacto del RESULTADO de list_classes)
* my_reservations -> muestra las reservas del usuario (activas y pasadas)
* cancel_reservation -> cancela una reserva por nombre de clase (pedir confirmacion antes)
* my_routines -> lista las rutinas del usuario con sus IDs
* delete_routine -> elimina una rutina por id_rutina (pedir confirmacion antes)
* edit_routine -> edita datos de una rutina (nombre/objetivo/nivel/duracion/dia/instrucciones)

REGLA CRITICA PARA RESERVAS:
- list_classes solo devuelve clases FUTURAS. Si el usuario pide reservar una clase, llama SIEMPRE
  a book_class con su id_clase. NUNCA respondas "ya ha tenido lugar" por tu cuenta sin llamar a book_class.
- El backend ya valida si la clase es pasada, llena o ya reservada. Confia en el RESULTADO.
- Si el RESULTADO de book_class contiene "exito":false, NUNCA digas que la reserva fue realizada.
  Informa del error concreto (campo "error") y sugiere alternativas.
- Para reservar, SIEMPRE usa el id_clase exacto del RESULTADO de list_classes.

REGLA CRITICA PARA CREAR RUTINAS:
Si el usuario quiere una rutina, NUNCA llames a create_routine directamente.
Primero recopila conversacionalmente, preguntando uno a uno si no los tienes:
1. Nombre de la rutina
2. Objetivo (definir / volumen / rendimiento / salud)
3. Nivel (principiante / intermedio / avanzado)
4. Duracion en minutos
5. Dia de la semana (lunes/martes/miercoles/jueves/viernes/sabado/domingo)
6. Instrucciones de la rutina (OBLIGATORIO preguntar: notas importantes, equipamiento, restricciones, etc.)
7. Ejercicios: PRIMERO propone 3-5 ejercicios especificos acordes al objetivo/nivel/grupo muscular.
   Usa nombres REALES y concisos: "Sentadilla con barra", "Prensa de piernas", "Curl femoral con mancuerna".
   NUNCA pongas descripciones como "con peso en espalda" o "peso adicional en...".
   ESPERA a que el usuario confirme o modifique. Solo tras confirmar llama a create_routine.

REGLA PARA EDITAR RUTINAS:
Cuando el usuario pida editar una rutina:
1. Identifica el nombre de la rutina que el usuario quiere editar.
2. Pregunta que desea cambiar: nombre, objetivo, nivel, duracion, dia de la semana o instrucciones.
3. El usuario responde con el nuevo valor.
4. SOLO entonces llama a edit_routine con nombre_rutina y los campos a cambiar.
NUNCA llames a edit_routine sin antes preguntar que desea cambiar.
NUNCA llames a my_routines solo para obtener un ID — edit_routine resuelve el ID internamente por nombre.

REGLA PARA CANCELAR O ELIMINAR:
Pide siempre confirmacion antes de cancel_reservation o delete_routine.
Usa SIEMPRE el nombre de la rutina o clase, NUNCA el ID numericos.
Para cancelar una clase PASADA, responde directamente con reply que no es posible cancelar clases ya realizadas.

FORMATO DE RESPUESTA - JSON puro, SIN texto fuera del JSON:
Respuesta normal: {$jsonReply}
Llamar herramienta: {$jsonTool}

Parametros create_routine: {"nombre":"","objetivo":"","nivel":"","duracion":45,"dia_semana":"lunes","instrucciones":"","ejercicios":[{"nombre_ejercicio":"Sentadilla con barra","grupo_muscular":"pierna","series":3,"repeticiones":"8-12","notas":""}]}
Parametros book_class: {"nombre_clase":"Nombre exacto de la clase a reservar"}
Parametros cancel_reservation: {"nombre_clase":"Nombre exacto de la clase"}
Parametros delete_routine: {"nombre_rutina":"Nombre exacto de la rutina"}
Parametros edit_routine: {"nombre_rutina":"Nombre actual de la rutina","nombre":"","objetivo":"","nivel":"","duracion":60,"dia_semana":"lunes","instrucciones":""}
Parametros list_classes / my_reservations / my_routines / progress_summary: {}
PROMPT;
    }

    // ─────────────────────────────────────────────────────────────────────────
    // EXTRACTOR JSON ROBUSTO
    // ─────────────────────────────────────────────────────────────────────────

    public function extractJson(string $raw): ?array
    {
        $raw = preg_replace('/<think>.*?<\/think>/s', '', $raw);
        $raw = trim($raw);

        $decoded = json_decode($raw, true);
        if (json_last_error() === JSON_ERROR_NONE && isset($decoded['action'])) return $decoded;

        $start = strpos($raw, '{');
        $end   = strrpos($raw, '}');
        if ($start === false || $end === false || $end <= $start) return null;

        $jsonStr = substr($raw, $start, $end - $start + 1);
        $decoded = json_decode($jsonStr, true);
        if (json_last_error() === JSON_ERROR_NONE && isset($decoded['action'])) return $decoded;

        return null;
    }

    // ─────────────────────────────────────────────────────────────────────────
    // DISPATCHER
    // ─────────────────────────────────────────────────────────────────────────

    private function executeTool(string $action, array $params, User $user): array
    {
        return match ($action) {
            'progress_summary'   => $this->toolProgressSummary($user),
            'create_routine'     => $this->toolCreateRoutine($params, $user),
            'list_classes'       => $this->toolListClasses(),
            'book_class'         => $this->toolBookClass($params, $user),
            'my_reservations'    => $this->toolMyReservations($user),
            'cancel_reservation' => $this->toolCancelReservation($params, $user),
            'my_routines'        => $this->toolMyRoutines($user),
            'delete_routine'     => $this->toolDeleteRoutine($params, $user),
            'edit_routine'       => $this->toolEditRoutine($params, $user),
            default              => ['error' => "Herramienta desconocida: {$action}"],
        };
    }

    // ─────────────────────────────────────────────────────────────────────────
    // TOOL: progress_summary
    // ─────────────────────────────────────────────────────────────────────────

    private function toolProgressSummary(User $user): array
    {
        $registros = DB::table('registros_progreso')
            ->where('id_usuario', $user->id_usuario)
            ->orderBy('fecha_registro', 'asc')->limit(10)->get();

        if ($registros->isEmpty()) return ['sin_datos' => true];

        $primero = $registros->first();
        $ultimo  = $registros->last();
        $difPeso = ($primero->peso_kg_registro && $ultimo->peso_kg_registro)
            ? round((float)$ultimo->peso_kg_registro - (float)$primero->peso_kg_registro, 2) : null;

        return [
            'total_registros' => $registros->count(),
            'inicio'  => ['fecha' => $primero->fecha_registro, 'peso' => $primero->peso_kg_registro],
            'ultimo'  => ['fecha' => $ultimo->fecha_registro,  'peso' => $ultimo->peso_kg_registro],
            'diferencia_peso' => $difPeso,
            'tendencia' => $difPeso === null ? null : ($difPeso < 0 ? 'bajando' : ($difPeso > 0 ? 'subiendo' : 'estable')),
        ];
    }

    // ─────────────────────────────────────────────────────────────────────────
    // TOOL: create_routine
    // ─────────────────────────────────────────────────────────────────────────

    private function toolCreateRoutine(array $params, User $user): array
    {
        $nombre      = $params['nombre']    ?? 'Rutina IA';
        $objetivo    = $params['objetivo']  ?? 'salud';
        $nivel       = $params['nivel']     ?? 'principiante';
        $duracion    = (int)($params['duracion'] ?? 45);
        $diaSemana   = $params['dia_semana'] ?? 'lunes';
        $instrucciones = $params['instrucciones'] ?? null;
        $ejercicios  = $params['ejercicios'] ?? [];

        $mapaDias = [
            'lunes'=>'lunes','martes'=>'martes','miércoles'=>'miercoles','miercoles'=>'miercoles',
            'jueves'=>'jueves','viernes'=>'viernes','sábado'=>'sabado','sabado'=>'sabado',
            'domingo'=>'domingo','descanso'=>'descanso',
        ];
        $diaSemana = $mapaDias[mb_strtolower($diaSemana)] ?? 'lunes';
        if (!in_array($objetivo, ['definir','volumen','rendimiento','salud'])) $objetivo = 'salud';
        if (!in_array($nivel, ['principiante','intermedio','avanzado'])) $nivel = 'principiante';

        try {
            return DB::transaction(function () use ($user, $nombre, $objetivo, $nivel, $duracion, $diaSemana, $instrucciones, $ejercicios) {
                $idRutina = DB::table('rutinas_usuario')->insertGetId([
                    'id_usuario'                => $user->id_usuario,
                    'nombre_rutina_usuario'     => $nombre,
                    'objetivo_rutina_usuario'   => $objetivo,
                    'nivel_rutina_usuario'      => $nivel,
                    'duracion_estimada_minutos' => $duracion,
                    'instrucciones_rutina'      => $instrucciones,
                    'origen_rutina'             => 'ia_coach',
                    'dia_semana'                => $diaSemana,
                    'rutina_activa'             => 1,
                    'fecha_creacion_rutina'     => now(),
                ]);

                $idsEj = [];
                $orden = 1;
                foreach ($ejercicios as $ej) {
                    $nombreEj   = trim($ej['nombre_ejercicio'] ?? ($ej['nombre'] ?? 'Ejercicio ' . $orden));
                    $grupoMusc  = mb_strtolower(trim($ej['grupo_muscular'] ?? $ej['grupo'] ?? 'fullbody'));

                    // 1) Búsqueda exacta (case-insensitive)
                    $existing = DB::table('ejercicios')
                        ->whereRaw('LOWER(nombre_ejercicio) = ?', [mb_strtolower($nombreEj)])
                        ->first();

                    // 2) Búsqueda fuzzy si no hay exacta
                    if (!$existing) {
                        $palabras = array_filter(explode(' ', mb_strtolower($nombreEj)));
                        $query = DB::table('ejercicios');
                        foreach ($palabras as $p) {
                            if (mb_strlen($p) > 3) {
                                $query->orWhereRaw('LOWER(nombre_ejercicio) LIKE ?', ["%{$p}%"]);
                            }
                        }
                        $existing = $query->first();
                    }

                    // 3) Si no existe en absoluto -> crear el ejercicio
                    if (!$existing) {
                        $idEj = DB::table('ejercicios')->insertGetId([
                            'nombre_ejercicio'         => $nombreEj,
                            'grupo_muscular_principal' => $grupoMusc,
                            'descripcion_ejercicio'    => 'Creado por IA Coach',
                        ]);
                    } else {
                        $idEj = $existing->id_ejercicio;
                    }

                    DB::table('rutinas_ejercicios')->insert([
                        'id_rutina_usuario'     => $idRutina,
                        'id_ejercicio'          => $idEj,
                        'orden_en_rutina'       => $orden,
                        'series_objetivo'       => (int)($ej['series'] ?? 3),
                        'repeticiones_objetivo' => (string)($ej['repeticiones'] ?? '10'),
                        'peso_objetivo_kg'      => isset($ej['peso_kg']) ? (float)$ej['peso_kg'] : null,
                        'notas_ejercicio'       => $ej['notas'] ?? $nombreEj,
                    ]);
                    $orden++;
                }

                return ['exito' => true, 'id_rutina' => $idRutina, 'nombre' => $nombre, 'dia_semana' => $diaSemana, 'ejercicios_creados' => $orden - 1];
            });
        } catch (\Throwable $e) {
            Log::error('[AiCoachService] create_routine: ' . $e->getMessage());
            return ['exito' => false, 'error' => $e->getMessage()];
        }
    }

    // ─────────────────────────────────────────────────────────────────────────
    // TOOL: list_classes — incluye DÍA DE LA SEMANA en español
    // ─────────────────────────────────────────────────────────────────────────

    private function toolListClasses(): array
    {
        // Solo clases FUTURAS — no mostrar clases que ya han pasado
        $clases = DB::table('clases_gimnasio')
            ->where('estado_clase', 'publicada')
            ->where('fecha_inicio_clase', '>', now())
            ->orderBy('fecha_inicio_clase', 'asc')
            ->get();

        return $clases->map(function ($c) {
            $reservasActivas = DB::table('reservas_clase')
                ->where('id_clase_gimnasio', $c->id_clase_gimnasio)
                ->where('estado_reserva', 'reservada')->count();

            $cupoDisponible = max(0, $c->cupo_maximo_clase - $reservasActivas);
            $ts      = strtotime($c->fecha_inicio_clase);
            $diaSem  = self::$DIAS_ES[date('l', $ts)] ?? date('l', $ts);
            $hora    = date('H:i', $ts);
            $horaFin = date('H:i', strtotime($c->fecha_fin_clase));

            // Formato COMPACTO para reducir tokens en el historial
            return [
                'nombre'     => $c->titulo_clase,
                'instructor' => $c->instructor_clase ?? 'N/A',
                'fecha'      => "{$diaSem} " . date('d/m/Y', $ts) . " {$hora}–{$horaFin}",
                'plazas'     => $cupoDisponible > 0 ? $cupoDisponible . ' disponibles' : 'LLENA',
            ];
        })->values()->toArray();
    }

    // ─────────────────────────────────────────────────────────────────────────
    // TOOL: book_class
    // ─────────────────────────────────────────────────────────────────────────

    private function toolBookClass(array $params, User $user): array
    {
        // Aceptar nombre_clase o id_clase
        $idClase     = isset($params['id_clase']) ? (int)$params['id_clase'] : 0;
        $nombreClase = $params['nombre_clase'] ?? null;

        if (!$idClase && !$nombreClase) {
            return ['exito' => false, 'error' => 'Indica el nombre de la clase que deseas reservar.'];
        }

        // Resolver nombre → id buscando en clases futuras publicadas
        if (!$idClase && $nombreClase) {
            $claseEncontrada = DB::table('clases_gimnasio')
                ->where('estado_clase', 'publicada')
                ->where('fecha_inicio_clase', '>', now())
                ->whereRaw('LOWER(titulo_clase) LIKE ?', ['%' . mb_strtolower($nombreClase) . '%'])
                ->orderBy('fecha_inicio_clase', 'asc')
                ->first();

            if (!$claseEncontrada) {
                return ['exito' => false, 'error' => "No se encontró ninguna clase futura llamada \"{$nombreClase}\". Usa list_classes para ver las clases disponibles."];
            }
            $idClase = $claseEncontrada->id_clase_gimnasio;
        }

        $clase = DB::table('clases_gimnasio')
            ->where('id_clase_gimnasio', $idClase)->where('estado_clase', 'publicada')->first();
        if (!$clase) return ['exito' => false, 'error' => "Clase no encontrada o no publicada."];

        // Bloquear reservas en clases pasadas
        if ($clase->fecha_inicio_clase <= now()) {
            return ['exito' => false, 'pasada' => true, 'error' => "La clase \"{$clase->titulo_clase}\" ya ha tenido lugar. No es posible reservar clases pasadas."];
        }

        // Comprobar si ya existe CUALQUIER registro para este usuario+clase
        $existeRegistro = DB::table('reservas_clase')
            ->where('id_usuario', $user->id_usuario)
            ->where('id_clase_gimnasio', $idClase)
            ->first();

        if ($existeRegistro) {
            if ($existeRegistro->estado_reserva === 'reservada') {
                return ['exito' => false, 'ya_reservada' => true, 'error' => 'Ya estás reservado en esta clase.'];
            }
            // Si estaba cancelada, reactivar
            DB::table('reservas_clase')
                ->where('id_usuario', $user->id_usuario)
                ->where('id_clase_gimnasio', $idClase)
                ->update(['estado_reserva' => 'reservada', 'fecha_reserva' => now(), 'origen_reserva' => 'ia_coach']);

            // Enviar email de confirmación
            try {
                $reservaModel = ReservaClase::find($existeRegistro->id_reserva_clase);
                if ($reservaModel) Mail::to($user->correo_usuario ?? $user->email)->send(new ReservationConfirmed($reservaModel));
            } catch (\Throwable $e) {
                Log::warning('[AiCoachService] Email reserva (reactivada): ' . $e->getMessage());
            }

            return ['exito' => true, 'id_reserva' => $existeRegistro->id_reserva_clase, 'clase' => $clase->titulo_clase, 'inicio' => $clase->fecha_inicio_clase, 'fin' => $clase->fecha_fin_clase, 'reactivada' => true];
        }

        // Comprobar cupo disponible
        $reservasActivas = DB::table('reservas_clase')
            ->where('id_clase_gimnasio', $idClase)->where('estado_reserva', 'reservada')->count();
        $cupoDisponible = max(0, $clase->cupo_maximo_clase - $reservasActivas);
        if ($cupoDisponible <= 0) return ['exito' => false, 'llena' => true, 'error' => 'La clase está llena.'];

        try {
            $idReserva = DB::table('reservas_clase')->insertGetId([
                'id_usuario'        => $user->id_usuario,
                'id_clase_gimnasio' => $idClase,
                'estado_reserva'    => 'reservada',
                'fecha_reserva'     => now()->toDateTimeString(),
                'origen_reserva'    => 'ia_coach',
            ]);

            if (!$idReserva) {
                return ['exito' => false, 'error' => 'Error al guardar la reserva en la base de datos.'];
            }

            // Enviar email de confirmación (igual que reserva manual)
            try {
                $reservaModel = ReservaClase::find($idReserva);
                if ($reservaModel) Mail::to($user->correo_usuario ?? $user->email)->send(new ReservationConfirmed($reservaModel));
            } catch (\Throwable $e) {
                Log::warning('[AiCoachService] Email reserva: ' . $e->getMessage());
            }

            return ['exito' => true, 'id_reserva' => $idReserva, 'clase' => $clase->titulo_clase, 'inicio' => $clase->fecha_inicio_clase, 'fin' => $clase->fecha_fin_clase];
        } catch (\Throwable $e) {
            Log::error('[AiCoachService] book_class: ' . $e->getMessage());
            return ['exito' => false, 'error' => 'Error al guardar la reserva. Por favor inténtalo de nuevo.'];
        }
    }

    private function saveAssistantMessage(int $convId, int $userId, string $content): ChatMensajeIA
    {
        return ChatMensajeIA::create([
            'id_conversacion' => $convId,
            'id_usuario'      => $userId,
            'rol'             => 'assistant',
            'contenido'       => $content,
        ]);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // TOOL: my_reservations — lista reservas del usuario (activas y pasadas)
    // ─────────────────────────────────────────────────────────────────────────

    private function toolMyReservations(User $user): array
    {
        $dias = ['Monday'=>'lunes','Tuesday'=>'martes','Wednesday'=>'miércoles',
            'Thursday'=>'jueves','Friday'=>'viernes','Saturday'=>'sábado','Sunday'=>'domingo'];

        $reservas = DB::table('reservas_clase')
            ->join('clases_gimnasio', 'reservas_clase.id_clase_gimnasio', '=', 'clases_gimnasio.id_clase_gimnasio')
            ->where('reservas_clase.id_usuario', $user->id_usuario)
            ->select(
                'reservas_clase.id_clase_gimnasio',
                'reservas_clase.estado_reserva',
                'clases_gimnasio.titulo_clase',
                'clases_gimnasio.fecha_inicio_clase',
                'clases_gimnasio.instructor_clase'
            )
            ->orderBy('clases_gimnasio.fecha_inicio_clase', 'desc')
            ->get();

        Log::info('[my_reservations] id_usuario=' . $user->id_usuario . ' | filas=' . $reservas->count());

        if ($reservas->isEmpty()) return [];

        return $reservas->map(function ($r) use ($dias) {
            $ts = strtotime($r->fecha_inicio_clase);
            $esFutura = $r->fecha_inicio_clase > now();
            return [
                'id_clase'    => $r->id_clase_gimnasio,
                'clase'       => $r->titulo_clase,
                'instructor'  => $r->instructor_clase ?? 'N/A',
                'fecha_inicio'=> $r->fecha_inicio_clase,
                'dia_semana'  => $dias[date('l', $ts)] ?? date('l', $ts),
                'cancelable'  => ($esFutura && $r->estado_reserva === 'reservada'),
                'estado'      => $r->estado_reserva,
            ];
        })->values()->toArray();
    }

    // ─────────────────────────────────────────────────────────────────────────
    // TOOL: cancel_reservation — cancela una reserva por id_clase
    // ─────────────────────────────────────────────────────────────────────────

    private function toolCancelReservation(array $params, User $user): array
    {
        // Aceptar tanto nombre_clase como id_clase para mayor flexibilidad
        $idClase     = $params['id_clase'] ?? null;
        $nombreClase = $params['nombre_clase'] ?? null;

        if (!$idClase && !$nombreClase) {
            return ['exito' => false, 'error' => 'Indica el nombre de la clase que deseas cancelar.'];
        }

        // Resolver nombre → id buscando en las reservas activas del usuario
        if (!$idClase && $nombreClase) {
            $reservaConNombre = DB::table('reservas_clase')
                ->join('clases_gimnasio', 'reservas_clase.id_clase_gimnasio', '=', 'clases_gimnasio.id_clase_gimnasio')
                ->where('reservas_clase.id_usuario', $user->id_usuario)
                ->where('reservas_clase.estado_reserva', 'reservada')
                ->whereRaw('LOWER(clases_gimnasio.titulo_clase) LIKE ?', ['%' . mb_strtolower($nombreClase) . '%'])
                ->select('clases_gimnasio.id_clase_gimnasio', 'clases_gimnasio.titulo_clase', 'clases_gimnasio.fecha_inicio_clase')
                ->first();

            if (!$reservaConNombre) {
                return ['exito' => false, 'error' => "No tienes ninguna reserva activa para una clase llamada \"{$nombreClase}\"."];
            }
            $idClase = $reservaConNombre->id_clase_gimnasio;
        }

        $clase = DB::table('clases_gimnasio')->where('id_clase_gimnasio', $idClase)->first();
        if (!$clase) return ['exito' => false, 'error' => "No se encontró la clase."];

        $reserva = DB::table('reservas_clase')
            ->where('id_usuario', $user->id_usuario)
            ->where('id_clase_gimnasio', $idClase)
            ->where('estado_reserva', 'reservada')
            ->first();

        if (!$reserva) return ['exito' => false, 'error' => 'No tienes una reserva activa en esa clase.'];

        if ($clase->fecha_inicio_clase <= now()) {
            return ['exito' => false, 'error' => 'No es posible cancelar una clase que ya ha comenzado o finalizado.'];
        }

        Log::info('[cancel_reservation] Intentando cancelar', [
            'id_usuario'    => $user->id_usuario,
            'id_clase'      => $idClase,
            'titulo_clase'  => $clase->titulo_clase,
            'reserva_estado'=> $reserva->estado_reserva ?? 'NOT_FOUND',
        ]);

        $affected = DB::table('reservas_clase')
            ->where('id_usuario', $user->id_usuario)
            ->where('id_clase_gimnasio', $idClase)
            ->where('estado_reserva', 'reservada')
            ->update(['estado_reserva' => 'cancelada']);

        Log::info('[cancel_reservation] Filas afectadas: ' . $affected);

        if ($affected === 0) {
            return ['exito' => false, 'error' => 'No se encontró una reserva activa para cancelar. Es posible que ya estuviera cancelada.'];
        }

        return ['exito' => true, 'clase' => $clase->titulo_clase];
    }

    // ─────────────────────────────────────────────────────────────────────────
    // TOOL: my_routines — lista las rutinas activas del usuario
    // ─────────────────────────────────────────────────────────────────────────

    private function toolMyRoutines(User $user): array
    {
        $rutinas = DB::table('rutinas_usuario')
            ->where(function ($q) use ($user) {
                $q->where('id_usuario', $user->id_usuario)
                  ->orWhere('origen_rutina', 'plantilla');
            })
            ->where('rutina_activa', 1)
            ->orderBy('fecha_creacion_rutina', 'desc')
            ->get();

        if ($rutinas->isEmpty()) return [];

        return $rutinas->map(function ($r) {
            $ejerciciosCount = DB::table('rutinas_ejercicios')
                ->where('id_rutina_usuario', $r->id_rutina_usuario)->count();
            return [
                'id'        => $r->id_rutina_usuario,
                'nombre'    => $r->nombre_rutina_usuario,
                'objetivo'  => $r->objetivo_rutina_usuario,
                'nivel'     => $r->nivel_rutina_usuario,
                'duracion'  => $r->duracion_estimada_minutos,
                'dia_semana'=> $r->dia_semana,
                'origen'    => $r->origen_rutina,
                'ejercicios'=> $ejerciciosCount,
            ];
        })->values()->toArray();
    }

    // ─────────────────────────────────────────────────────────────────────────
    // TOOL: delete_routine — elimina una rutina del usuario
    // ─────────────────────────────────────────────────────────────────────────

    private function toolDeleteRoutine(array $params, User $user): array
    {
        // Aceptar nombre_rutina o id_rutina
        $idRutina    = $params['id_rutina']    ?? null;
        $nombreBusca = $params['nombre_rutina'] ?? null;

        if (!$idRutina && !$nombreBusca) {
            return ['exito' => false, 'error' => 'Indica el nombre de la rutina que deseas eliminar.'];
        }

        // Resolver nombre → id (solo en rutinas del usuario, no plantillas de admin)
        if (!$idRutina && $nombreBusca) {
            $encontrada = DB::table('rutinas_usuario')
                ->where('id_usuario', $user->id_usuario)
                ->whereRaw('LOWER(nombre_rutina_usuario) LIKE ?', ['%' . mb_strtolower($nombreBusca) . '%'])
                ->first();

            if (!$encontrada) {
                return ['exito' => false, 'error' => "No se encontró ninguna rutina propia llamada \"{$nombreBusca}\" en tu cuenta."];
            }
            $idRutina = $encontrada->id_rutina_usuario;
        }

        $rutina = DB::table('rutinas_usuario')
            ->where('id_rutina_usuario', $idRutina)
            ->where('id_usuario', $user->id_usuario)
            ->first();

        if (!$rutina) return ['exito' => false, 'error' => 'No se encontró la rutina en tu cuenta.'];

        try {
            DB::transaction(function () use ($idRutina) {
                DB::table('rutinas_ejercicios')->where('id_rutina_usuario', $idRutina)->delete();
                DB::table('rutinas_usuario')->where('id_rutina_usuario', $idRutina)->delete();
            });
            return ['exito' => true, 'nombre' => $rutina->nombre_rutina_usuario];
        } catch (\Throwable $e) {
            Log::error('[AiCoachService] delete_routine: ' . $e->getMessage());
            return ['exito' => false, 'error' => 'Error al eliminar la rutina. Inténtalo de nuevo.'];
        }
    }

    // ─────────────────────────────────────────────────────────────────────────
    // TOOL: edit_routine — edita datos básicos de una rutina del usuario
    // ─────────────────────────────────────────────────────────────────────────

    private function toolEditRoutine(array $params, User $user): array
    {
        // Normalizar dia_semana: quitar tildes y pasar a minúsculas
        $mapaDias = [
            'lunes'=>'lunes','martes'=>'martes','miércoles'=>'miercoles','miercoles'=>'miercoles',
            'jueves'=>'jueves','viernes'=>'viernes','sábado'=>'sabado','sabado'=>'sabado',
            'domingo'=>'domingo','descanso'=>'descanso',
        ];

        // Aceptar nombre_rutina o id_rutina
        $idRutina    = $params['id_rutina']    ?? null;
        $nombreBusca = $params['nombre_rutina'] ?? null;

        if (!$idRutina && !$nombreBusca) {
            return ['exito' => false, 'error' => 'Indica el nombre de la rutina que deseas editar.'];
        }

        // Resolver nombre → id buscando en rutinas del usuario
        if (!$idRutina && $nombreBusca) {
            $encontrada = DB::table('rutinas_usuario')
                ->where('id_usuario', $user->id_usuario)
                ->whereRaw('LOWER(nombre_rutina_usuario) LIKE ?', ['%' . mb_strtolower($nombreBusca) . '%'])
                ->first();

            if (!$encontrada) {
                return ['exito' => false, 'error' => "No se encontró ninguna rutina propia llamada \"{$nombreBusca}\". Asegúrate de escribir el nombre correctamente."];
            }
            $idRutina = $encontrada->id_rutina_usuario;
        }

        $rutina = DB::table('rutinas_usuario')
            ->where('id_rutina_usuario', $idRutina)
            ->where('id_usuario', $user->id_usuario)
            ->first();

        if (!$rutina) return ['exito' => false, 'error' => 'No se encontró la rutina en tu cuenta.'];

        $campos = [];
        if (!empty($params['nombre']))      $campos['nombre_rutina_usuario']     = $params['nombre'];
        if (!empty($params['objetivo']))    $campos['objetivo_rutina_usuario']   = $params['objetivo'];
        if (!empty($params['nivel']))       $campos['nivel_rutina_usuario']      = $params['nivel'];
        if (isset($params['duracion']))     $campos['duracion_estimada_minutos'] = (int)$params['duracion'];
        if (!empty($params['dia_semana'])) {
            $diaRaw = mb_strtolower(trim($params['dia_semana']));
            $campos['dia_semana'] = $mapaDias[$diaRaw] ?? $diaRaw;
        }
        if (isset($params['instrucciones']))$campos['instrucciones_rutina']      = $params['instrucciones'];

        if (empty($campos)) return ['exito' => false, 'error' => 'No se proporcionaron campos para actualizar.'];

        $affected = DB::table('rutinas_usuario')
            ->where('id_rutina_usuario', $idRutina)
            ->update($campos);

        if ($affected === false) {
            return ['exito' => false, 'error' => 'No se pudo actualizar la rutina. Inténtalo de nuevo.'];
        }

        $nombreFinal = $campos['nombre_rutina_usuario'] ?? $rutina->nombre_rutina_usuario;
        $diaFinal    = $campos['dia_semana']            ?? $rutina->dia_semana;
        return ['exito' => true, 'id_rutina' => $idRutina, 'nombre' => $nombreFinal, 'dia_semana' => $diaFinal];
    }
}
