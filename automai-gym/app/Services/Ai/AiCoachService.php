<?php

namespace App\Services\Ai;

use App\Models\ChatConversacion;
use App\Models\ChatMensajeIA;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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

        $historial = ChatMensajeIA::where('id_conversacion', $conversationId)
            ->orderBy('id', 'desc')
            ->limit(30)
            ->get()->reverse()->values();

        $messages = [['role' => 'system', 'content' => $systemPrompt]];
        foreach ($historial as $msg) {
            if ($msg->rol === 'assistant') {
                $messages[] = ['role' => 'assistant', 'content' => $msg->contenido];
            } elseif ($msg->rol === 'tool') {
                // Inyectar resultados de herramientas como contexto del usuario para que el LLM los recuerde
                $messages[] = ['role' => 'user', 'content' => '[RESULTADO ' . strtoupper($msg->tool_name ?? 'TOOL') . ']: ' . $msg->contenido];
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

        // Construir mensaje final legible directamente desde el resultado
        $finalMsg = $this->buildHumanReadableResult($action, $toolResult);

        // Intentar mejorar con segunda llamada al LLM
        try {
            $msgs2 = $messages;
            $msgs2[] = ['role' => 'assistant', 'content' => json_encode($parsed)];
            $msgs2[] = [
                'role'    => 'user',
                'content' => 'RESULTADO: ' . json_encode($toolResult, JSON_UNESCAPED_UNICODE)
                    . "\n\nRedacta SOLO: {\"action\":\"reply\",\"message\":\"respuesta amigable en español al usuario, sin mencionar JSON ni action ni params\"}",
            ];

            $finalRaw    = $this->ollama->chat($msgs2);
            $finalParsed = $this->extractJson($finalRaw);

            if ($finalParsed && ($finalParsed['action'] ?? '') === 'reply' && !empty($finalParsed['message'])) {
                $finalMsg = $finalParsed['message'];
            }
        } catch (\Throwable $e) {
            Log::warning('[AiCoachService] Segunda llamada falló, usando fallback: ' . $e->getMessage());
        }

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
                : (isset($result['llena']) && $result['llena']
                    ? "❌ Lo siento, esa clase está llena. ¿Quieres que te busque otra clase disponible?"
                    : (isset($result['ya_reservada']) && $result['ya_reservada']
                        ? "⚠️ Ya tienes una reserva activa en esa clase. No se puede hacer una reserva duplicada. ¿Quieres apuntarte a otra clase?"
                        : "❌ No pude hacer la reserva. " . ($result['error'] ?? 'Inténtalo de nuevo.'))),

            'list_classes' => empty($result)
                ? "No hay clases publicadas en este momento."
                : $this->formatClassList($result),

            'progress_summary' => isset($result['sin_datos'])
                ? "Todavía no tienes registros de progreso. Empieza a registrar tu peso y medidas en la sección «Progreso»."
                : $this->formatProgressSummary($result),

            default => json_encode($result, JSON_UNESCAPED_UNICODE),
        };
    }

    private function formatClassList(array $clases): string
    {
        $meses = [
            1=>'enero',2=>'febrero',3=>'marzo',4=>'abril',5=>'mayo',6=>'junio',
            7=>'julio',8=>'agosto',9=>'septiembre',10=>'octubre',11=>'noviembre',12=>'diciembre',
        ];
        $lineas = ["Estas son las clases disponibles:\n"];
        foreach ($clases as $c) {
            $ts = strtotime($c['fecha_inicio']);
            $diaSemana = $c['dia_semana'];
            $dia  = date('j', $ts);
            $mes  = $meses[(int)date('n', $ts)] ?? '';
            $anio = date('Y', $ts);
            $fechaLegible = "{$diaSemana} {$dia} de {$mes} de {$anio}";
            $estado = $c['estado'] === 'llena' ? '🔴 LLENA' : "🟢 {$c['cupo_disponible']} plazas";
            $lineas[] = "**{$c['nombre']}** — {$fechaLegible}, {$c['hora']} (instructor: {$c['instructor']}) — {$estado}";
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

        $tonoDesc = $tono === 'motivador' ? 'Usa un tono motivador y entusiasta.' : 'Usa un tono directo y profesional.';

        // IMPORTANTE: usar variables para los ejemplos de JSON para evitar problemas con llaves en heredoc
        $jsonReply     = '{"action":"reply","message":"texto en espanol"}';
        $jsonTool      = '{"action":"NOMBRE","params":{...}}';
        $jsonRoutine   = '{"nombre":"","objetivo":"","nivel":"","duracion":45,"dia_semana":"lunes","instrucciones":"","ejercicios":[{"nombre_ejercicio":"","series":3,"repeticiones":"8-12","notas":""}]}';
        $jsonBookClass = '{"id_clase":1}';

        return <<<PROMPT
Eres IA Coach, el entrenador personal inteligente de AutomAI Gym. {$tonoDesc} Responde siempre en espanol.

PERFIL DEL USUARIO ({$nombre}):
- Objetivo: {$objetivo} | Nivel: {$nivel} | Dias/semana: {$dias}
- Peso: {$peso} | Altura: {$altura}
- Progreso reciente: {$progresoStr}

HERRAMIENTAS DISPONIBLES - activarlas solo cuando tengas TODA la informacion:
* progress_summary -> analiza progreso del usuario
* create_routine -> crea rutina en la BD (solo tras confirmacion del usuario)
* list_classes -> lista clases del gimnasio con fechas y horarios
* book_class -> reserva una clase (usa el id_clase correcto del RESULTADO de list_classes)

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

FORMATO DE RESPUESTA - JSON puro, SIN texto fuera del JSON:
Respuesta normal: {$jsonReply}
Llamar herramienta: {$jsonTool}

Parametros para create_routine: {"nombre":"","objetivo":"","nivel":"","duracion":45,"dia_semana":"lunes","instrucciones":"","ejercicios":[{"nombre_ejercicio":"Sentadilla con barra","grupo_muscular":"pierna","series":3,"repeticiones":"8-12","notas":""}]}
Parametros para book_class: {$jsonBookClass}  <-- usa el id_clase del RESULTADO de list_classes
Parametros para list_classes: {}
Parametros para progress_summary: {}
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
            'progress_summary' => $this->toolProgressSummary($user),
            'create_routine'   => $this->toolCreateRoutine($params, $user),
            'list_classes'     => $this->toolListClasses(),
            'book_class'       => $this->toolBookClass($params, $user),
            default            => ['error' => "Herramienta desconocida: {$action}"],
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
        $clases = DB::table('clases_gimnasio')->where('estado_clase', 'publicada')->get();

        return $clases->map(function ($c) {
            $reservasActivas = DB::table('reservas_clase')
                ->where('id_clase_gimnasio', $c->id_clase_gimnasio)
                ->where('estado_reserva', 'reservada')->count();

            $cupoDisponible = max(0, $c->cupo_maximo_clase - $reservasActivas);
            $ts     = strtotime($c->fecha_inicio_clase);
            $diaSemana = self::$DIAS_ES[date('l', $ts)] ?? date('l', $ts);
            $hora   = date('H:i', $ts);
            $horaFin = date('H:i', strtotime($c->fecha_fin_clase));

            return [
                'id_clase'        => $c->id_clase_gimnasio,
                'nombre'          => $c->titulo_clase,
                'instructor'      => $c->instructor_clase ?? 'N/A',
                'dia_semana'      => $diaSemana,
                'hora'            => "{$hora}–{$horaFin}",
                'fecha_inicio'    => $c->fecha_inicio_clase,
                'cupo_maximo'     => $c->cupo_maximo_clase,
                'cupo_disponible' => $cupoDisponible,
                'estado'          => $cupoDisponible > 0 ? 'disponible' : 'llena',
            ];
        })->values()->toArray();
    }

    // ─────────────────────────────────────────────────────────────────────────
    // TOOL: book_class
    // ─────────────────────────────────────────────────────────────────────────

    private function toolBookClass(array $params, User $user): array
    {
        $idClase = (int)($params['id_clase'] ?? 0);
        if (!$idClase) return ['exito' => false, 'error' => 'No se especificó id_clase.'];

        $clase = DB::table('clases_gimnasio')
            ->where('id_clase_gimnasio', $idClase)->where('estado_clase', 'publicada')->first();
        if (!$clase) return ['exito' => false, 'error' => "Clase {$idClase} no encontrada."];

        // Comprobar si ya existe CUALQUIER registro para este usuario+clase
        // (la tabla tiene UNIQUE KEY uq_usuario_clase en id_usuario, id_clase_gimnasio)
        $existeRegistro = DB::table('reservas_clase')
            ->where('id_usuario', $user->id_usuario)
            ->where('id_clase_gimnasio', $idClase)
            ->first();

        if ($existeRegistro) {
            if ($existeRegistro->estado_reserva === 'reservada') {
                return ['exito' => false, 'ya_reservada' => true, 'error' => 'Ya estás reservado en esta clase.'];
            }
            // Si estaba cancelada, reactivar la reserva existente (UPDATE en lugar de INSERT)
            DB::table('reservas_clase')
                ->where('id_usuario', $user->id_usuario)
                ->where('id_clase_gimnasio', $idClase)
                ->update([
                    'estado_reserva' => 'reservada',
                    'fecha_reserva'  => now(),
                    'origen_reserva' => 'ia_coach',
                ]);
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
                'fecha_reserva'     => now(),
                'origen_reserva'    => 'ia_coach',
            ]);
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
}
