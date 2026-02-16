<?php

use Livewire\Component;
use App\Models\RutinaUsuario;
use App\Models\Ejercicio;

new class extends Component {
    public $editingRoutine = null;

    // Form fields
    public $nombre = '';
    public $objetivo = '';
    public $nivel = 'intermedio';
    public $duracion = 60;
    public $instrucciones = '';
    public $selectedExercises = []; // IDs of selected exercises

    // Filters
    public $exerciseSearch = '';
    public $muscleGroup = '';

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
        $this->selectedExercises = [];
    }

    public function with(): array
    {
        return [
            'rutinas' => RutinaUsuario::where('origen_rutina', 'plantilla')->get(),
            'availableExercises' => Ejercicio::where('nombre_ejercicio', 'like', '%' . $this->exerciseSearch . '%')
                ->when($this->muscleGroup, function ($q) {
                    return $q->where('grupo_muscular_principal', $this->muscleGroup);
                })
                ->get(),
            'muscleGroups' => ['pecho', 'espalda', 'pierna', 'hombro', 'biceps', 'triceps', 'core', 'cardio', 'fullbody'],
        ];
    }
};
?>

<div class="grid2">
    <!-- Lista de Rutinas -->
    <div class="panel">
        <div class="panel-h" style="display: flex; justify-content: space-between; align-items: center; gap: 10px;">
            <strong>Rutinas Master (Plantillas)</strong>
            <button class="mini-btn primary" wire:click="createRoutine()">+ Nueva Rutina</button>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Nombre / Objetivo</th>
                        <th>Nivel</th>
                        <th>Duración</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($rutinas as $rutina)
                        <tr>
                            <td>
                                <div style="font-weight:800;color:var(--cream);">{{ $rutina->nombre_rutina_usuario }}</div>
                                <div style="color:rgba(255,255,255,0.7); font-size:12px;">
                                    {{ ucfirst($rutina->objetivo_rutina_usuario) }}</div>
                            </td>
                            <td><span class="pill">{{ $rutina->nivel_rutina_usuario }}</span></td>
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

    <!-- Formulario -->
    @if($editingRoutine)
        <aside class="panel">
            <div class="panel-h"><strong>{{ $editingRoutine === 'new' ? 'Nueva Rutina Master' : 'Editar Rutina' }}</strong>
            </div>
            <form class="form" wire:submit.prevent="saveRoutine">
                <div class="field">
                    <label>Nombre de la Rutina</label>
                    <input type="text" class="input" wire:model="nombre" required>
                </div>
                <div class="field">
                    <label>Objetivo Principal</label>
                    <select wire:model="objetivo" class="input" required>
                        <option value="">Seleccionar objetivo</option>
                        <option value="definir">Definir</option>
                        <option value="volumen">Volumen</option>
                        <option value="rendimiento">Rendimiento</option>
                        <option value="salud">Salud</option>
                    </select>
                </div>
                <div class="row">
                    <div class="field">
                        <label>Nivel Sugerido</label>
                        <select wire:model="nivel" class="input">
                            <option value="principiante">Principiante</option>
                            <option value="intermedio">Intermedio</option>
                            <option value="avanzado">Avanzado</option>
                        </select>
                    </div>
                    <div class="field">
                        <label>Duración (min)</label>
                        <input type="number" class="input" wire:model="duracion">
                    </div>
                </div>
                <div class="field">
                    <label>Instrucciones Generales</label>
                    <textarea class="input" wire:model="instrucciones"></textarea>
                </div>

                <div class="divider"></div>

                <div class="field">
                    <label>Ejercicios Seleccionados ({{ count($selectedExercises) }})</label>
                    <div style="display:flex; gap:10px; margin-bottom:10px;">
                        <input type="text" class="input" placeholder="Filtrar ejercicios..."
                            wire:model.live="exerciseSearch">
                        <select wire:model.live="muscleGroup" style="width:150px;">
                            <option value="">Todos</option>
                            @foreach($muscleGroups as $mg)
                                <option value="{{ $mg }}">{{ ucfirst($mg) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="exercise-grid"
                        style="max-height: 200px; overflow-y: auto; display: grid; grid-template-columns: repeat(auto-fill, minmax(140px, 1fr)); gap: 8px;">
                        @foreach($availableExercises as $ejercicio)
                            <div wire:click="toggleExercise({{ $ejercicio->id_ejercicio }})"
                                class="exercise-item {{ in_array($ejercicio->id_ejercicio, $selectedExercises) ? 'selected' : '' }}"
                                style="padding:8px; border:1px solid var(--cream-4); border-radius:8px; cursor:pointer; font-size:11px; text-align:center;">
                                <div style="font-weight:bold; color:var(--cream);">{{ $ejercicio->nombre_ejercicio }}</div>
                                <div class="muted" style="font-size:9px;">{{ ucfirst($ejercicio->grupo_muscular_principal) }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <style>
                    .exercise-item.selected {
                        background: var(--primary);
                        border-color: var(--primary);
                    }

                    .exercise-item.selected .muted {
                        color: rgba(255, 255, 255, 0.7);
                    }
                </style>
                <div class="divider"></div>
                <div class="row" style="margin-top:10px;">
                    <button type="button" class="mini-btn primary"
                        wire:click="$set('editingRoutine', null)">Cancelar</button>
                    <button type="submit" class="cta" style="height:40px;justify-content:center;">Guardar</button>
                </div>
            </form>
        </aside>
    @endif
</div>