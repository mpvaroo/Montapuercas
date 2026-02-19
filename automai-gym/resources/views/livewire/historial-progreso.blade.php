<div>
    {{-- ══════════════════════════════════════════
         BARRA DE FILTROS
    ══════════════════════════════════════════ --}}
    <div class="filter-bar">

        {{-- Rango de fechas --}}
        <div class="filter-group">
            <label class="filter-label">Desde</label>
            <input class="filter-input" type="date" wire:model.live="dateFrom" />
        </div>
        <div class="filter-group">
            <label class="filter-label">Hasta</label>
            <input class="filter-input" type="date" wire:model.live="dateTo" />
        </div>

        {{-- Filtro notas --}}
        <div class="filter-group">
            <label class="filter-label">Notas</label>
            <div class="filter-select-wrap">
                <select class="filter-input filter-select" wire:model.live="filterNotes">
                    <option value="all">Todos</option>
                    <option value="with">Con nota</option>
                    <option value="without">Sin nota</option>
                </select>
            </div>
        </div>

        {{-- Ordenar por (selector rápido) --}}
        <div class="filter-group">
            <label class="filter-label">Ordenar por</label>
            <div class="filter-select-wrap">
                <select class="filter-input filter-select" wire:model.live="sortField">
                    <option value="fecha_registro">Fecha</option>
                    <option value="peso_kg_registro">Peso</option>
                    <option value="cintura_cm_registro">Cintura</option>
                    <option value="pecho_cm_registro">Pecho</option>
                    <option value="cadera_cm_registro">Cadera</option>
                </select>
            </div>
        </div>

        {{-- Dirección --}}
        <div class="filter-group">
            <label class="filter-label">Dirección</label>
            <div class="filter-select-wrap">
                <select class="filter-input filter-select" wire:model.live="sortDir">
                    <option value="desc">↓ Mayor → Menor</option>
                    <option value="asc">↑ Menor → Mayor</option>
                </select>
            </div>
        </div>

        {{-- Limpiar filtros --}}
        @if ($hasActiveFilters)
            <div class="filter-group" style="justify-content:flex-end;align-self:flex-end;">
                <button class="filter-clear" type="button" wire:click="clearFilters">
                    ✕ Limpiar filtros
                </button>
            </div>
        @endif

        {{-- Indicador de carga --}}
        <div wire:loading class="filter-loading">
            <span class="filter-spinner"></span>
        </div>
    </div>

    {{-- ══════════════════════════════════════════
         TABLA
    ══════════════════════════════════════════ --}}
    @if ($records->isEmpty())
        <div class="hist-empty">No hay registros que coincidan con los filtros aplicados.</div>
    @else
        <div style="overflow-x:auto; margin-top:4px;">
            <table class="hist-table">
                <thead>
                    <tr>
                        @php
                            $cols = [
                                'fecha_registro' => 'Fecha',
                                'peso_kg_registro' => 'Peso (kg)',
                                'cintura_cm_registro' => 'Cintura (cm)',
                                'pecho_cm_registro' => 'Pecho (cm)',
                                'cadera_cm_registro' => 'Cadera (cm)',
                            ];
                        @endphp

                        @foreach ($cols as $field => $label)
                            <th wire:click="sortBy('{{ $field }}')" class="th-sortable"
                                style="cursor:pointer; user-select:none;">
                                {{ $label }}
                                @if ($sortField === $field)
                                    <span class="sort-icon">{{ $sortDir === 'asc' ? '↑' : '↓' }}</span>
                                @else
                                    <span class="sort-icon-muted">⇅</span>
                                @endif
                            </th>
                        @endforeach

                        <th>Notas</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($records as $record)
                        <tr>
                            <td class="td-fecha">{{ $record->fecha_registro->format('Y-m-d') }}</td>
                            <td>{{ $record->peso_kg_registro !== null ? number_format($record->peso_kg_registro, 2) : '—' }}
                            </td>
                            <td>{{ $record->cintura_cm_registro !== null ? number_format($record->cintura_cm_registro, 2) : '—' }}
                            </td>
                            <td>{{ $record->pecho_cm_registro !== null ? number_format($record->pecho_cm_registro, 2) : '—' }}
                            </td>
                            <td>{{ $record->cadera_cm_registro !== null ? number_format($record->cadera_cm_registro, 2) : '—' }}
                            </td>
                            <td class="td-nota">{{ $record->notas_progreso ?? '—' }}</td>
                            <td>
                                <a href="{{ route('progreso.detalle', $record->id_registro_progreso) }}"
                                    class="td-btn">VER</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Paginación --}}
        @if ($records->hasPages())
            <div class="hist-pagination">
                {{ $records->links() }}
            </div>
        @endif

        <div class="hist-count">
            Mostrando {{ $records->firstItem() }}–{{ $records->lastItem() }} de {{ $records->total() }} registros
        </div>
    @endif
</div>
