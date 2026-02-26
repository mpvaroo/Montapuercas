<?php

use Livewire\Component;
use App\Models\ClaseGimnasio;
use App\Models\TipoClase;

use Livewire\WithPagination;
use Livewire\Attributes\Url;

new class extends Component {
    use WithPagination;

    public $editingClass = null;

    // Sorting
    #[Url(as: 'orden')]
    public $sortField = 'fecha_inicio_clase';
    #[Url(as: 'dir')]
    public $sortDir = 'desc';

    // Filters (Keep Date & Search)
    public $search = '';
    public $dateFilter = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }
    public function updatingDateFilter()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDir = $this->sortDir === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDir = 'asc'; // Default asc for text, maybe desc for dates? Let's stick to asc default toggle
        }
        $this->resetPage();
    }

    // Form fields
    public $id_tipo_clase = '';
    public $titulo = '';
    public $instructor = '';
    public $inicio = '';
    public $fin = '';
    public $cupo = 20;
    public $estado = 'publicada';

    public function createClass()
    {
        $this->resetForm();
        $this->editingClass = 'new';
    }

    public function editClass($id)
    {
        $clase = ClaseGimnasio::findOrFail($id);
        $this->editingClass = $clase->id_clase_gimnasio;
        $this->id_tipo_clase = $clase->id_tipo_clase;
        $this->titulo = $clase->titulo_clase;
        $this->instructor = $clase->instructor_clase;
        $this->inicio = $clase->fecha_inicio_clase?->format('Y-m-d\TH:i');
        $this->fin = $clase->fecha_fin_clase?->format('Y-m-d\TH:i');
        $this->cupo = $clase->cupo_maximo_clase;
        $this->estado = $clase->estado_clase;
    }

    public function saveClass()
    {
        $this->validate(
            [
                'id_tipo_clase' => 'required|exists:tipos_clase,id_tipo_clase',
                'titulo' => 'required|string|max:150',
                'instructor' => 'required|string|max:100',
                'inicio' => 'required|date',
                'fin' => 'nullable|date|after:inicio',
                'cupo' => 'required|numeric|min:1|max:100',
                'estado' => 'required|in:publicada,borrador,cancelada',
            ],
            [
                'id_tipo_clase.required' => 'Debes seleccionar un tipo de clase.',
                'id_tipo_clase.exists' => 'El tipo de clase seleccionado no es válido.',
                'titulo.required' => 'El título de la clase es obligatorio.',
                'titulo.max' => 'El título no puede exceder los 150 caracteres.',
                'instructor.required' => 'El nombre del instructor es obligatorio.',
                'instructor.max' => 'El nombre del instructor no puede exceder los 100 caracteres.',
                'inicio.required' => 'La fecha de inicio es obligatoria.',
                'inicio.date' => 'La fecha de inicio no es válida.',
                'fin.date' => 'La fecha de fin no es válida.',
                'fin.after' => 'La fecha de fin debe ser posterior a la de inicio.',
                'cupo.required' => 'El cupo máximo es obligatorio.',
                'cupo.min' => 'El cupo debe ser al menos de 1 persona.',
                'cupo.max' => 'El cupo no puede superar las 100 personas.',
                'estado.required' => 'El estado es obligatorio.',
                'estado.in' => 'El estado seleccionado no es válido.',
            ],
        );

        $inicioDate = new \DateTime($this->inicio);
        $finDate = $this->fin ? new \DateTime($this->fin) : (clone $inicioDate)->modify('+1 hour');

        $data = [
            'id_tipo_clase' => $this->id_tipo_clase,
            'titulo_clase' => $this->titulo,
            'instructor_clase' => $this->instructor ?: 'Instructor Gym',
            'fecha_inicio_clase' => $inicioDate->format('Y-m-d H:i:s'),
            'fecha_fin_clase' => $finDate->format('Y-m-d H:i:s'),
            'cupo_maximo_clase' => $this->cupo,
            'estado_clase' => $this->estado ?: 'publicada',
        ];

        if ($this->editingClass === 'new') {
            ClaseGimnasio::create($data);
        } else {
            ClaseGimnasio::findOrFail($this->editingClass)->update($data);
        }

        $this->editingClass = null;
        $this->resetPage();
        $this->dispatch('notify', 'Clase guardada correctamente.');
    }

    public function deleteClass($id)
    {
        ClaseGimnasio::findOrFail($id)->delete();
        $this->resetPage();
        $this->dispatch('notify', 'Clase eliminada.');
    }

    public function resetForm()
    {
        $this->id_tipo_clase = '';
        $this->titulo = '';
        $this->instructor = '';
        $this->inicio = '';
        $this->fin = '';
        $this->cupo = 20;
        $this->estado = 'publicada';
    }

    public function with(): array
    {
        // Whitelist sort fields
        $allowed = ['titulo_clase', 'fecha_inicio_clase', 'cupo_maximo_clase', 'estado_clase'];
        $sort = in_array($this->sortField, $allowed) ? $this->sortField : 'fecha_inicio_clase';

        return [
            'clases' => ClaseGimnasio::with('tipoClase')
                ->where(function ($q) {
                    $q->where('titulo_clase', 'like', '%' . $this->search . '%')->orWhere('instructor_clase', 'like', '%' . $this->search . '%');
                })
                ->when($this->dateFilter, function ($q) {
                    $q->whereDate('fecha_inicio_clase', $this->dateFilter);
                })
                ->orderBy($sort, $this->sortDir)
                ->paginate(10),
            'tipos' => TipoClase::all(),
        ];
    }
};
?>

<div class="grid2">
    <!-- Lista de Clases -->
    <div class="panel">
        <div class="panel-h"
            style="display: flex; justify-content: space-between; align-items: center; gap: 15px; flex-wrap: wrap;">
            <div style="display: flex; align-items: center; gap: 10px;">
                <strong>Gestión de Clases</strong>
            </div>

            <div style="display: flex; gap: 10px; flex-grow: 1; justify-content: flex-end; align-items: center;">
                <!-- Filtros (Solo Fecha y Búsqueda) -->
                <input type="date"
                    style="width: 130px; height: 32px; font-size: 12px; padding: 0 10px; background: rgba(255,255,255,0.05); border: 1px solid var(--cream-4); color: var(--cream);"
                    wire:model.live="dateFilter">

                <input type="text" placeholder="Buscar clase..."
                    style="width: 150px; height: 32px; font-size: 12px; padding: 0 10px; background: rgba(255,255,255,0.05); border: 1px solid var(--cream-4); color: var(--cream);"
                    wire:model.live="search">

                <button class="mini-btn primary" wire:click="createClass()" style="height: 32px; padding: 0 15px;">+
                    Nueva</button>
            </div>
        </div>
        <div class="table-wrap" style="max-height: 460px; overflow-y: auto;">
            <table>
                <thead style="position: sticky; top: 0; background: #000; z-index: 1;">
                    <tr>
                        <th wire:click="sortBy('titulo_clase')" style="cursor:pointer; user-select:none;">
                            Clase / Instructor
                            @if ($sortField === 'titulo_clase')
                                <span style="color:var(--cream);">{{ $sortDir === 'asc' ? '↑' : '↓' }}</span>
                            @else
                                <span style="color:rgba(255,255,255,0.2);">⇅</span>
                            @endif
                        </th>
                        <th wire:click="sortBy('fecha_inicio_clase')" style="cursor:pointer; user-select:none;">
                            Horario
                            @if ($sortField === 'fecha_inicio_clase')
                                <span style="color:var(--cream);">{{ $sortDir === 'asc' ? '↑' : '↓' }}</span>
                            @else
                                <span style="color:rgba(255,255,255,0.2);">⇅</span>
                            @endif
                        </th>
                        <th wire:click="sortBy('cupo_maximo_clase')" style="cursor:pointer; user-select:none;">
                            Cupo
                            @if ($sortField === 'cupo_maximo_clase')
                                <span style="color:var(--cream);">{{ $sortDir === 'asc' ? '↑' : '↓' }}</span>
                            @else
                                <span style="color:rgba(255,255,255,0.2);">⇅</span>
                            @endif
                        </th>
                        <th wire:click="sortBy('estado_clase')" style="cursor:pointer; user-select:none;">
                            Estado
                            @if ($sortField === 'estado_clase')
                                <span style="color:var(--cream);">{{ $sortDir === 'asc' ? '↑' : '↓' }}</span>
                            @else
                                <span style="color:rgba(255,255,255,0.2);">⇅</span>
                            @endif
                        </th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($clases as $clase)
                        <tr>
                            <td>
                                <div style="font-weight:800;color:var(--cream);">{{ $clase->titulo_clase }}</div>
                                <div class="muted">{{ $clase->instructor_clase }}
                                    ({{ $clase->tipoClase->nombre_tipo_clase }})
                                </div>
                            </td>
                            <td>
                                <div style="font-size:11px; color:var(--cream);">
                                    {{ $clase->fecha_inicio_clase?->format('d/m H:i') }}
                                </div>
                            </td>
                            <td style="color:var(--cream);">
                                {{ $clase->reservas_count ?? $clase->reservas()->count() }}/{{ $clase->cupo_maximo_clase }}
                            </td>
                            <td>
                                <div class="state">
                                    <span
                                        class="dot {{ $clase->estado_clase === 'publicada' ? 'green' : ($clase->estado_clase === 'cancelada' ? 'red' : 'warn') }}"></span>
                                    {{ ucfirst($clase->estado_clase) }}
                                </div>
                            </td>
                            <td>
                                <div class="actions">
                                    <button class="mini-btn warn"
                                        wire:click="editClass({{ $clase->id_clase_gimnasio }})">Edit</button>
                                    <button class="mini-btn danger"
                                        wire:click="deleteClass({{ $clase->id_clase_gimnasio }})"
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
    @if ($editingClass)
        <aside class="panel">
            <div class="panel-h"><strong>{{ $editingClass === 'new' ? 'Nueva Clase' : 'Editar Clase' }}</strong></div>
            <form class="form" wire:submit.prevent="saveClass">
                <div class="field">
                    <label>Título de la Clase</label>
                    <input type="text" class="input" wire:model="titulo" required>
                    @error('titulo')
                        <span
                            style="color:#ef4444; font-size:11px; margin-top:4px; display:block;">{{ $message }}</span>
                    @enderror
                </div>
                <div class="field">
                    <label>Tipo de Clase</label>
                    <select wire:model="id_tipo_clase" required>
                        <option value="">Seleccionar tipo</option>
                        @foreach ($tipos as $tipo)
                            <option value="{{ $tipo->id_tipo_clase }}">{{ $tipo->nombre_tipo_clase }}</option>
                        @endforeach
                    </select>
                    @error('id_tipo_clase')
                        <span
                            style="color:#ef4444; font-size:11px; margin-top:4px; display:block;">{{ $message }}</span>
                    @enderror
                </div>
                <div class="field">
                    <label>Instructor</label>
                    <input type="text" class="input" wire:model="instructor" required>
                    @error('instructor')
                        <span
                            style="color:#ef4444; font-size:11px; margin-top:4px; display:block;">{{ $message }}</span>
                    @enderror
                </div>
                <div class="row">
                    <div class="field">
                        <label>Fecha Inicio</label>
                        <input type="datetime-local" class="input" wire:model="inicio" required>
                        @error('inicio')
                            <span
                                style="color:#ef4444; font-size:11px; margin-top:4px; display:block;">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="field">
                        <label>Fecha Fin</label>
                        <input type="datetime-local" class="input" wire:model="fin">
                        @error('fin')
                            <span
                                style="color:#ef4444; font-size:11px; margin-top:4px; display:block;">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="row">
                    <div class="field">
                        <label>Cupo Máximo</label>
                        <input type="number" class="input" wire:model="cupo" required>
                        @error('cupo')
                            <span
                                style="color:#ef4444; font-size:11px; margin-top:4px; display:block;">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="field">
                        <label>Estado</label>
                        <select wire:model="estado" class="input">
                            <option value="publicada">Publicada</option>
                            <option value="borrador">Borrador</option>
                            <option value="cancelada">Cancelada</option>
                        </select>
                        @error('estado')
                            <span
                                style="color:#ef4444; font-size:11px; margin-top:4px; display:block;">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="divider"></div>
                <div class="row" style="margin-top:10px;">
                    <button type="button" class="mini-btn primary"
                        wire:click="$set('editingClass', null)">Cancelar</button>
                    <button type="submit" class="cta" style="height:40px;justify-content:center;">Guardar</button>
                </div>
            </form>
        </aside>
    @endif
</div>
