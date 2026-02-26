<?php

use Livewire\Component;
use App\Models\RutinaUsuario;
use App\Models\Ejercicio;
use Illuminate\Support\Facades\Auth;

new class extends Component {
    public $showModal = false;
    public $filter = 'all'; // all, usuario, plantilla

    // Form fields
    public $nombre = '';
    public $objetivo = 'salud';
    public $nivel = 'principiante';
    public $duracion = 60;
    public $instrucciones = '';
    public $dia_semana = '';
    public $selectedExercises = [];

    // Exercise search/filter
    public $exerciseSearch = '';
    public $muscleGroup = '';

    public function toggleModal()
    {
        $this->showModal = !$this->showModal;
        if ($this->showModal) {
            $this->resetForm();
        }
    }

    public function resetForm()
    {
        $this->nombre = '';
        $this->objetivo = 'salud';
        $this->nivel = 'principiante';
        $this->duracion = 60;
        $this->instrucciones = '';
        $this->dia_semana = '';
        $this->selectedExercises = [];
    }

    public function toggleExercise($id)
    {
        if (in_array($id, $this->selectedExercises)) {
            $this->selectedExercises = array_diff($this->selectedExercises, [$id]);
        } else {
            $this->selectedExercises[] = $id;
        }
    }

    public function saveRoutine()
    {
        $this->validate(
            [
                'nombre' => 'required|string|max:140',
                'objetivo' => 'required|string|in:salud,definir,volumen,rendimiento',
                'nivel' => 'required|string|in:principiante,intermedio,avanzado',
                'duracion' => 'nullable|integer|min:1|max:480',
                'dia_semana' => 'nullable|string|in:lunes,martes,miercoles,jueves,viernes,sabado,domingo,descanso',
                'instrucciones' => 'nullable|string|max:1000',
            ],
            [
                'nombre.required' => 'El nombre de la rutina es obligatorio.',
                'nombre.max' => 'El nombre no puede exceder los 140 caracteres.',
                'objetivo.required' => 'Selecciona un objetivo principal.',
                'objetivo.in' => 'El objetivo principal no es válido.',
                'nivel.required' => 'Selecciona un nivel de dificultad.',
                'nivel.in' => 'El nivel de dificultad no es válido.',
                'duracion.min' => 'La duración debe ser al menos 1 minuto.',
                'duracion.max' => 'La duración máxima permitida es de 480 minutos.',
                'dia_semana.in' => 'El día programado no es válido.',
                'instrucciones.max' => 'Las instrucciones son demasiado largas.',
            ],
        );

        $routine = RutinaUsuario::create([
            'id_usuario' => Auth::id(),
            'nombre_rutina_usuario' => $this->nombre,
            'objetivo_rutina_usuario' => $this->objetivo,
            'nivel_rutina_usuario' => $this->nivel,
            'duracion_estimada_minutos' => $this->duracion,
            'instrucciones_rutina' => $this->instrucciones,
            'dia_semana' => $this->dia_semana ?: null,
            'origen_rutina' => 'usuario',
            'rutina_activa' => true,
        ]);

        if (!empty($this->selectedExercises)) {
            foreach ($this->selectedExercises as $index => $exId) {
                $routine->ejercicios()->attach($exId, ['orden_en_rutina' => $index + 1]);
            }
        }

        $this->showModal = false;
        $this->dispatch('notify', 'Rutina creada correctamente.');
        return redirect()->route('detalle-rutina', $routine->id_rutina_usuario);
    }

    public function deleteRoutine($id)
    {
        $routine = RutinaUsuario::where('id_rutina_usuario', $id)->where('id_usuario', Auth::id())->firstOrFail();

        if ($routine->origen_rutina === 'plantilla') {
            $this->dispatch('error', 'No puedes eliminar una plantilla.');
            return;
        }

        $routine->delete();
        $this->dispatch('notify', 'Rutina eliminada.');
    }

    public function with(): array
    {
        $query = RutinaUsuario::query();

        if ($this->filter === 'usuario') {
            $query->where('id_usuario', Auth::id())->where('origen_rutina', 'usuario');
        } elseif ($this->filter === 'plantilla') {
            $query->where('origen_rutina', 'plantilla');
        } else {
            // 'all' - Show user's routines OR templates
            $query->where(function ($q) {
                $q->where('id_usuario', Auth::id())->orWhere('origen_rutina', 'plantilla');
            });
        }

        return [
            'routines' => $query->get(),
            'availableExercises' => Ejercicio::where('nombre_ejercicio', 'like', '%' . $this->exerciseSearch . '%')
                ->when($this->muscleGroup, function ($q) {
                    return $q->where('grupo_muscular_principal', $this->muscleGroup);
                })
                ->get(),
            'muscleGroups' => ['pecho', 'espalda', 'pierna', 'hombro', 'biceps', 'triceps', 'core', 'cardio', 'fullbody'],
            'days' => ['lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado', 'domingo', 'descanso'],
        ];
    }
};
?>

<div>
    <!-- Success/Error Alerts -->
    @if (session('success'))
        <div
            style="background: rgba(74, 222, 128, 0.1); border: 1px solid #4ade80; color: #4ade80; padding: 12px; border-radius: 12px; margin-bottom: 20px;">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div
            style="background: rgba(239, 68, 68, 0.1); border: 1px solid #ef4444; color: #ef4444; padding: 12px; border-radius: 12px; margin-bottom: 20px;">
            {{ session('error') }}
        </div>
    @endif

    <!-- Action Bar -->
    <div class="actions">
        <button wire:click="toggleModal" class="btn-action btn-primary" style="border:none; outline:none;">
            <span>+</span> Crear Nueva Rutina
        </button>
        <a href="{{ route('calendario') }}" class="btn-action btn-secondary">
            <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24">
                <path
                    d="M19 4h-1V2h-2v2H8V2H6v2H5c-1.11 0-1.99.9-1.99 2L3 20a2 2 0 0 0 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V10h14v10zm0-12H5V6h14v2z" />
            </svg>
            Calendario de Entrenamientos
        </a>
        <div class="filter-group" style="display: flex; align-items: center; gap: 10px; margin-left: auto;">
            <span
                style="font-size: 11px; text-transform: uppercase; color: rgba(239,231,214,.5); font-weight: 700;">Mostrar:</span>
            <select wire:model.live="filter" class="input-modal"
                style="width: 160px; height: 38px; font-size: 13px; margin: 0;">
                <option value="all">Todas</option>
                <option value="usuario">Mis Rutinas</option>
                <option value="plantilla">Plantillas</option>
            </select>
        </div>
    </div>

    <!-- Routine Cards Grid -->
    <div class="routines-grid">
        @forelse($routines as $index => $rutina)
            <div
                class="routine-card {{ $index % 3 == 0 ? 'focus-top' : ($index % 3 == 2 ? 'full-width focus-low' : '') }}">
                <img class="routine-img" src="{{ asset('img/rutina-' . (($index % 3) + 1) . '.png') }}"
                    alt="{{ $rutina->nombre_rutina_usuario }}">
                <div class="routine-frame"></div>
                <div class="routine-overlay">
                    <div style="{{ $index % 3 == 2 ? 'max-width: 520px;' : '' }}">
                        <h3>{{ $rutina->nombre_rutina_usuario }}</h3>
                        <div class="routine-details">
                            <div class="detail"><span class="check-ico">✓</span>
                                {{ $rutina->dia_semana ? ucfirst($rutina->dia_semana) : 'Libre' }}</div>
                            <div class="detail"><span class="check-ico">✓</span> Duración:
                                {{ $rutina->duracion_estimada_minutos }} min
                            </div>
                            <div class="detail"><span class="check-ico">✓</span> Nivel:
                                {{ ucfirst($rutina->nivel_rutina_usuario) }}
                            </div>
                            <div class="detail"><span class="check-ico">👤</span> Origen:
                                <b>{{ $rutina->origen_rutina === 'plantilla' ? 'Plantilla' : ($rutina->origen_rutina === 'ia_coach' ? 'IA Coach' : 'Usuario') }}</b>
                            </div>
                        </div>
                        <div class="card-actions"
                            style="{{ $index % 3 == 2 ? 'grid-template-columns: 140px 140px; justify-content: start;' : '' }}">
                            <a href="{{ route('detalle-rutina', $rutina->id_rutina_usuario) }}" class="btn-card"
                                style="text-decoration:none; display:flex; align-items:center; justify-content:center;">Ver
                                Rutina</a>

                            @if ($rutina->origen_rutina !== 'plantilla')
                                <button wire:click="deleteRoutine({{ $rutina->id_rutina_usuario }})"
                                    wire:confirm="¿Estás seguro de que quieres eliminar esta rutina?" class="btn-card"
                                    style="width: 100%; margin-top: 8px; border-color: rgba(239, 68, 68, 0.3); color: #ef4444; background: rgba(239, 68, 68, 0.05);">
                                    Eliminar Rutina
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div
                style="grid-column: 1 / -1; text-align: center; color: var(--cream); padding: 40px; background: rgba(0,0,0,0.2); border-radius: 12px;">
                <p>No tienes rutinas activas en esta categoría. ¡Crea una nueva o pide ayuda a tu IA Coach!</p>
            </div>
        @endforelse
    </div>

    <!-- Modal para crear rutina -->
    @if ($showModal)
        <div class="modal-backdrop" style="display: flex;">
            <div class="modal">
                <div class="modal-head">
                    <div>
                        <h3>Diseñar nueva rutina</h3>
                        <p>GESTIÓN DE RUTINAS</p>
                    </div>
                    <button class="modal-close" wire:click="toggleModal">✕</button>
                </div>
                <form wire:submit.prevent="saveRoutine" style="display: contents;">
                    <div class="modal-body">
                        <div class="field">
                            <label class="label">Nombre de la rutina</label>
                            <input class="input-modal" type="text" wire:model="nombre"
                                placeholder="Ej: Empuje Hipertrofia" required>
                            @error('nombre')
                                <span
                                    style="color:#ef4444; font-size:11px; margin-top:4px; display:block;">{{ $message }}</span>
                            @enderror
                        </div>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                            <div class="field">
                                <label class="label">Objetivo</label>
                                <select class="input-modal" wire:model="objetivo" required>
                                    <option value="rendimiento">Fuerza / Rendimiento</option>
                                    <option value="volumen">Hipertrofia / Volumen</option>
                                    <option value="definir">Resistencia / Definir</option>
                                    <option value="salud">Salud General</option>
                                </select>
                                @error('objetivo')
                                    <span
                                        style="color:#ef4444; font-size:11px; margin-top:4px; display:block;">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="field">
                                <label class="label">Nivel</label>
                                <select class="input-modal" wire:model="nivel" required>
                                    <option value="principiante">Principiante</option>
                                    <option value="intermedio">Intermedio</option>
                                    <option value="avanzado">Avanzado</option>
                                </select>
                                @error('nivel')
                                    <span
                                        style="color:#ef4444; font-size:11px; margin-top:4px; display:block;">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                            <div class="field">
                                <label class="label">Duración (min)</label>
                                <input class="input-modal" type="number" wire:model="duracion">
                                @error('duracion')
                                    <span
                                        style="color:#ef4444; font-size:11px; margin-top:4px; display:block;">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="field">
                                <label class="label">Día programado</label>
                                <select class="input-modal" wire:model="dia_semana">
                                    <option value="">Libre</option>
                                    @foreach ($days as $day)
                                        <option value="{{ $day }}">{{ ucfirst($day) }}</option>
                                    @endforeach
                                </select>
                                @error('dia_semana')
                                    <span
                                        style="color:#ef4444; font-size:11px; margin-top:4px; display:block;">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="field">
                            <label class="label">Instrucciones</label>
                            <textarea class="input-modal" wire:model="instrucciones" style="height:60px;"></textarea>
                            @error('instrucciones')
                                <span
                                    style="color:#ef4444; font-size:11px; margin-top:4px; display:block;">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="divider"></div>

                        <div class="field">
                            <label class="label">Seleccionar Ejercicios ({{ count($selectedExercises) }})</label>
                            <div style="display:flex; gap:10px; margin-bottom:10px;">
                                <input type="text" class="input-modal" placeholder="Filtrar ejercicios..."
                                    wire:model.live="exerciseSearch" style="height:38px;">
                                <select wire:model.live="muscleGroup" class="input-modal"
                                    style="width:140px; height:38px;">
                                    <option value="">Todos</option>
                                    @foreach ($muscleGroups as $mg)
                                        <option value="{{ $mg }}">{{ ucfirst($mg) }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="exercise-grid-live"
                                style="max-height: 220px; overflow-y: auto; display: grid; grid-template-columns: repeat(auto-fill, minmax(130px, 1fr)); gap: 8px; padding: 5px;">
                                @foreach ($availableExercises as $ex)
                                    <div wire:click="toggleExercise({{ $ex->id_ejercicio }})"
                                        class="ex-item-live {{ in_array($ex->id_ejercicio, $selectedExercises) ? 'selected' : '' }}">
                                        <div class="ex-name">{{ $ex->nombre_ejercicio }}</div>
                                        <div class="ex-muscle">{{ ucfirst($ex->grupo_muscular_principal) }}</div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="modal-foot">
                        <button type="button" class="modal-btn secondary" wire:click="toggleModal">CANCELAR</button>
                        <button type="submit" class="modal-btn primary">CREAR RUTINA</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <style>
        .divider {
            height: 1px;
            background: rgba(239, 231, 214, 0.1);
            margin: 15px 0;
        }

        .ex-item-live {
            padding: 10px;
            border: 1px solid rgba(239, 231, 214, 0.1);
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.2s;
            background: rgba(255, 255, 255, 0.02);
            text-align: center;
        }

        .ex-item-live:hover {
            background: rgba(255, 255, 255, 0.05);
            border-color: rgba(239, 231, 214, 0.2);
        }

        .ex-item-live.selected {
            background: rgba(190, 145, 85, 0.2);
            border-color: var(--primary);
            box-shadow: inset 0 0 10px rgba(190, 145, 85, 0.1);
        }

        .ex-name {
            font-size: 11px;
            font-weight: 800;
            color: var(--cream);
            margin-bottom: 2px;
        }

        .ex-muscle {
            font-size: 9px;
            color: rgba(239, 231, 214, 0.5);
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .ex-item-live.selected .ex-name {
            color: #fff;
        }
    </style>
</div>
