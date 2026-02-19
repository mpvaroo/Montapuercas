<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class HistorialProgreso extends Component
{
    use WithPagination;

    // Filtros — se sincronizan con la URL
    #[Url(as: 'orden', history: true)]
    public string $sortField = 'fecha_registro';

    #[Url(as: 'dir', history: true)]
    public string $sortDir = 'desc';

    #[Url(as: 'desde', history: true)]
    public string $dateFrom = '';

    #[Url(as: 'hasta', history: true)]
    public string $dateTo = '';

    #[Url(as: 'notas', history: true)]
    public string $filterNotes = 'all'; // all | with | without

    public int $perPage = 20;

    // Reset paginación cuando cambia cualquier filtro
    public function updatedSortField(): void
    {
        $this->resetPage();
    }
    public function updatedSortDir(): void
    {
        $this->resetPage();
    }
    public function updatedDateFrom(): void
    {
        $this->resetPage();
    }
    public function updatedDateTo(): void
    {
        $this->resetPage();
    }
    public function updatedFilterNotes(): void
    {
        $this->resetPage();
    }

    public function sortBy(string $field): void
    {
        if ($this->sortField === $field) {
            $this->sortDir = $this->sortDir === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField  = $field;
            $this->sortDir = 'desc';
        }
        $this->resetPage();
    }

    public function clearFilters(): void
    {
        $this->dateFrom     = '';
        $this->dateTo       = '';
        $this->filterNotes  = 'all';
        $this->sortField    = 'fecha_registro';
        $this->sortDir      = 'desc';
        $this->resetPage();
    }

    public function render()
    {
        $user = Auth::user();

        // Campos válidos para ordenar
        $allowed = ['fecha_registro', 'peso_kg_registro', 'cintura_cm_registro', 'pecho_cm_registro', 'cadera_cm_registro'];
        $field   = in_array($this->sortField, $allowed) ? $this->sortField : 'fecha_registro';
        $dir     = $this->sortDir === 'asc' ? 'asc' : 'desc';

        $query = $user->registrosProgreso();

        // --- Rango de fechas ---
        if ($this->dateFrom !== '') {
            $query->whereDate('fecha_registro', '>=', $this->dateFrom);
        }
        if ($this->dateTo !== '') {
            $query->whereDate('fecha_registro', '<=', $this->dateTo);
        }

        // --- Filtro de notas ---
        if ($this->filterNotes === 'with') {
            $query->whereNotNull('notas_progreso')->where('notas_progreso', '!=', '');
        } elseif ($this->filterNotes === 'without') {
            $query->where(function ($q) {
                $q->whereNull('notas_progreso')->orWhere('notas_progreso', '');
            });
        }

        $records = $query->orderBy($field, $dir)->paginate($this->perPage);

        $hasActiveFilters = $this->dateFrom !== '' || $this->dateTo !== '' || $this->filterNotes !== 'all';

        return view('livewire.historial-progreso', [
            'records'          => $records,
            'hasActiveFilters' => $hasActiveFilters,
        ]);
    }
}
