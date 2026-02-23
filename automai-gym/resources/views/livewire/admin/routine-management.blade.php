<?php

use Livewire\Component;
use App\Models\RutinaUsuario;
use App\Models\Ejercicio;

use Livewire\WithPagination;
use Livewire\Attributes\Url;

new class extends Component {
    use WithPagination;

    public $editingRoutine = null;

    // Sorting
    #[Url(as: 'orden')]
    public $sortField = 'nombre_rutina_usuario';
    #[Url(as: 'dir')]
    public $sortDir = 'asc';

    // Filters
    public $routineSearch = '';

    public function updatingRoutineSearch()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDir = $this->sortDir === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDir = 'asc';
        }
        $this->resetPage();
    }

    // Form fields
    public $nombre = '';
    public $objetivo = '';
    public $nivel = 'intermedio';
    public $duracion = 60;
    public $instrucciones = '';
    public $selectedExercises = []; // IDs of selected exercises

    // Filters (for form exercises)
    public $exerciseSearch = '';
    public $muscleGroup = '';

    public $dia_semana = '';

    public function createRoutine()
    {
        $this->resetForm();
        $this->editingRoutine = 'new';
    }

    public function editRoutine($id)
    {
        $rutina = RutinaUsuario::with('ejercicios')->findOrFail($id);
        $this->editingRoutine = $rutina->id_rutina_usuario;
        $this->nombre = $rutina->nombre_rutina_usuario;
        $this->objetivo = $rutina->objetivo_rutina_usuario;
        $this->nivel = $rutina->nivel_rutina_usuario;
        $this->duracion = $rutina->duracion_estimada_minutos;
        $this->instrucciones = $rutina->instrucciones_rutina;
        $this->dia_semana = $rutina->dia_semana;
        $this->selectedExercises = $rutina->ejercicios->pluck('id_ejercicio')->toArray();
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
        $data = [
            'id_usuario' => Auth::id(),
            'nombre_rutina_usuario' => $this->nombre,
            'objetivo_rutina_usuario' => $this->objetivo,
            'nivel_rutina_usuario' => $this->nivel,
            'duracion_estimada_minutos' => $this->duracion,
            'instrucciones_rutina' => $this->instrucciones,
            'dia_semana' => $this->dia_semana,
            'origen_rutina' => 'plantilla',
            'rutina_activa' => true,
        ];

        if ($this->editingRoutine === 'new') {
            $rutina = RutinaUsuario::create($data);
        } else {
            $rutina = RutinaUsuario::findOrFail($this->editingRoutine);
            $rutina->update($data);
        }

        // Sync exercises
        $rutina->ejercicios()->sync($this->selectedExercises);

        $this->editingRoutine = null;
        $this->dispatch('notify', 'Rutina Master guardada.');
    }

    public function deleteRoutine($id)
    {
        RutinaUsuario::findOrFail($id)->delete();
        $this->dispatch('notify', 'Rutina eliminada.');
    }

    public function resetForm()
    {
        $this->nombre = '';
        $this->objetivo = '';
        $this->nivel = 'intermedio';
        $this->duracion = 60;
        $this->instrucciones = '';
        $this->dia_semana = '';
        $this->selectedExercises = [];
    }

    public function with(): array
    {
        // Whitelist sort fields
        $allowed = ['nombre_rutina_usuario', 'dia_semana', 'duracion_estimada_minutos'];
        $sort = in_array($this->sortField, $allowed) ? $this->sortField : 'nombre_rutina_usuario';

        return [
            'rutinas' => RutinaUsuario::where('origen_rutina', 'plantilla')
                ->where('id_usuario', Auth::id())
                ->where('nombre_rutina_usuario', 'like', '%' . $this->routineSearch . '%')
                ->orderBy($sort, $this->sortDir)
                ->paginate(10),
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

<div class="grid2">
    <!-- Lista de Rutinas -->
    <div class="panel">
        <div class="panel-h"
            style="display: flex; justify-content: space-between; align-items: center; gap: 15px; flex-wrap: wrap;">
            <div style="display: flex; align-items: center; gap: 10px;">
                <strong>Mis Rutinas y Plantillas</strong>
            </div>

            <div
                style="display: flex; gap: 10px; flex-grow: 1; justify-content: flex-end; align-items: center; flex-wrap: wrap;">
                <!-- Filtros (Solo Búsqueda) -->
                <input type="text" placeholder="Buscar rutina..."
                    style="width: 140px; height: 32px; font-size: 12px; padding: 0 10px; background: rgba(255,255,255,0.05); border: 1px solid var(--cream-4); color: var(--cream);"
                    wire:model.live="routineSearch">

                <button class="mini-btn primary" wire:click="createRoutine()" style="height: 32px; padding: 0 15px;">+
                    Nueva</button>
            </div>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th wire:click="sortBy('nombre_rutina_usuario')" style="cursor:pointer; user-select:none;">
                            Nombre / Objetivo
                            @if ($sortField === 'nombre_rutina_usuario')
                                <span style="color:var(--cream);">{{ $sortDir === 'asc' ? '↑' : '↓' }}</span>
                            @else
                                <span style="color:rgba(255,255,255,0.2);">⇅</span>
                            @endif
                        </th>
                        <th wire:click="sortBy('dia_semana')" style="cursor:pointer; user-select:none;">
                            Día / Nivel
                            @if ($sortField === 'dia_semana')
                                <span style="color:var(--cream);">{{ $sortDir === 'asc' ? '↑' : '↓' }}</span>
                            @else
                                <span style="color:rgba(255,255,255,0.2);">⇅</span>
                            @endif
                        </th>
                        <th wire:click="sortBy('duracion_estimada_minutos')" style="cursor:pointer; user-select:none;">
                            Duración
                            @if ($sortField === 'duracion_estimada_minutos')
                                <span style="color:var(--cream);">{{ $sortDir === 'asc' ? '↑' : '↓' }}</span>
                            @else
                                <span style="color:rgba(255,255,255,0.2);">⇅</span>
                            @endif
                        </th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($rutinas as $rutina)
                        <tr>
                            <td>
                                <div style="font-weight:800;color:var(--cream);">{{ $rutina->nombre_rutina_usuario }}
                                </div>
                                <div style="color:rgba(255,255,255,0.7); font-size:12px;">
                                    {{ ucfirst($rutina->objetivo_rutina_usuario) }}</div>
                            </td>
                            <td>
                                <div
                                    style="font-weight:800;color:var(--cream);font-size:11px;text-transform:uppercase;">
                                    {{ $rutina->dia_semana ?? 'Sin asignar' }}</div>
                                <span class="pill">{{ $rutina->nivel_rutina_usuario }}</span>
                            </td>
                            <td style="color:white;">{{ $rutina->duracion_estimada_minutos }} min</td>
                            <td>
                                <div class="actions">
                                    <button class="mini-btn warn"
                                        wire:click="editRoutine({{ $rutina->id_rutina_usuario }})">Edit</button>
                                    <button class="mini-btn danger"
                                        wire:click="deleteRoutine({{ $rutina->id_rutina_usuario }})"
                                        onclick="confirm('¿Seguro?') || event.stopImmediatePropagation()">X</button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Formulario (Modal) -->
    @if ($editingRoutine)
        <div class="modal-backdrop" style="display: flex;">
            <div class="modal">
                <div class="modal-head">
                    <div>
                        <h3>{{ $editingRoutine === 'new' ? 'Nueva Rutina Master' : 'Editar Rutina' }}</h3>
                        <p>GESTIÓN DE PLANTILLAS</p>
                    </div>
                    <button class="modal-close" wire:click="$set('editingRoutine', null)">✕</button>
                </div>
                <form class="modal-body" wire:submit.prevent="saveRoutine" id="routineForm">
                    <div class="field">
                        <label>Nombre de la Rutina</label>
                        <input type="text" class="field-input" wire:model="nombre" required>
                    </div>
                    <div class="field">
                        <label>Objetivo Principal</label>
                        <select wire:model="objetivo" class="field-input" required>
                            <option value="">Seleccionar objetivo...</option>
                            <option value="definir">Definir</option>
                            <option value="volumen">Volumen</option>
                            <option value="rendimiento">Rendimiento</option>
                            <option value="salud">Salud</option>
                        </select>
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 12px;">
                        <div class="field">
                            <label>Nivel Sugerido</label>
                            <select wire:model="nivel" class="field-input">
                                <option value="principiante">Principiante</option>
                                <option value="intermedio">Intermedio</option>
                                <option value="avanzado">Avanzado</option>
                            </select>
                        </div>
                        <div class="field">
                            <label>Día Programado</label>
                            <select wire:model="dia_semana" class="field-input">
                                <option value="">Sin asignar</option>
                                @foreach ($days as $day)
                                    <option value="{{ $day }}">{{ ucfirst($day) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="field">
                            <label>Duración (min)</label>
                            <input type="number" class="field-input" wire:model="duracion">
                        </div>
                    </div>
                    <div class="field">
                        <label>Instrucciones Generales</label>
                        <textarea class="field-input" wire:model="instrucciones" style="height: 60px;"></textarea>
                    </div>

                    <div class="divider" style="height: 1px; background: rgba(239, 231, 214, 0.1); margin: 10px 0;">
                    </div>

                    <div class="field">
                        <label>Seleccionar Ejercicios ({{ count($selectedExercises) }})</label>
                        <div style="display:flex; gap:10px; margin-bottom:10px;">
                            <input type="text" class="field-input" placeholder="Filtrar ejercicios..."
                                wire:model.live="exerciseSearch" style="height: 38px;">
                            <select wire:model.live="muscleGroup" class="field-input"
                                style="width:150px; height: 38px;">
                                <option value="">Todos</option>
                                @foreach ($muscleGroups as $mg)
                                    <option value="{{ $mg }}">{{ ucfirst($mg) }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="exercise-grid"
                            style="max-height: 180px; overflow-y: auto; display: grid; grid-template-columns: repeat(auto-fill, minmax(130px, 1fr)); gap: 8px; padding: 4px;">
                            @foreach ($availableExercises as $ejercicio)
                                <div wire:click="toggleExercise({{ $ejercicio->id_ejercicio }})"
                                    class="exercise-item {{ in_array($ejercicio->id_ejercicio, $selectedExercises) ? 'selected' : '' }}"
                                    style="padding:10px; border:1px solid rgba(239, 231, 214, 0.1); border-radius:12px; cursor:pointer; font-size:11px; text-align:center; transition: all 0.2s; background: rgba(255, 255, 255, 0.02);">
                                    <div style="font-weight:800; color:var(--cream);">
                                        {{ $ejercicio->nombre_ejercicio }}
                                    </div>
                                    <div class="muted"
                                        style="font-size:9px; color: rgba(239, 231, 214, 0.5); text-transform: uppercase;">
                                        {{ ucfirst($ejercicio->grupo_muscular_principal) }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <style>
                        .exercise-item.selected {
                            background: rgba(190, 145, 85, 0.2) !important;
                            border-color: rgba(190, 145, 85, 0.4) !important;
                        }
                    </style>
                </form>
                <div class="modal-foot">
                    <button type="button" class="modal-btn secondary"
                        wire:click="$set('editingRoutine', null)">CANCELAR</button>
                    <button type="submit" form="routineForm" class="modal-btn primary">GUARDAR PLANTILLA</button>
                </div>
            </div>
        </div>
    @endif
</div>
