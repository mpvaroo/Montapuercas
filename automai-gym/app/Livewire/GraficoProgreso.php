<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Livewire\Component;

class GraficoProgreso extends Component
{
    public string $metric = 'peso_kg_registro';

    /** Datos del gráfico sincronizados automáticamente con Alpine/JS via Livewire */
    public array $chartData = [];

    public static array $metrics = [
        'peso_kg_registro'    => ['label' => 'Peso',    'unit' => 'kg', 'color' => 'rgba(190,145,85,0.90)'],
        'cintura_cm_registro' => ['label' => 'Cintura', 'unit' => 'cm', 'color' => 'rgba(137,195,180,0.90)'],
        'pecho_cm_registro'   => ['label' => 'Pecho',   'unit' => 'cm', 'color' => 'rgba(180,140,210,0.90)'],
        'cadera_cm_registro'  => ['label' => 'Cadera',  'unit' => 'cm', 'color' => 'rgba(210,140,140,0.90)'],
    ];

    public function mount(): void
    {
        $this->refreshChartData();
    }

    /**
     * Livewire llama automáticamente a updatedMetric() cada vez que $this->metric cambia.
     * Esto sucede ANTES de render(), así que chartData ya estará actualizado en el próximo render.
     */
    public function updatedMetric(): void
    {
        $this->refreshChartData();
    }

    public function selectMetric(string $m): void
    {
        if (array_key_exists($m, self::$metrics)) {
            $this->metric = $m;
            $this->refreshChartData(); // Necesario: updatedMetric no dispara en cambios server-side
        }
    }

    private function refreshChartData(): void
    {
        $user = Auth::user();
        $meta = self::$metrics[$this->metric];

        // Agrupar todos los registros del usuario por semana ISO
        $allByWeek = $user->registrosProgreso()
            ->orderBy('fecha_registro', 'desc')
            ->get()
            ->groupBy(function ($r) {
                return Carbon::parse($r->fecha_registro)
                    ->startOfWeek(Carbon::MONDAY)
                    ->format('Y-m-d');
            });

        // 10 semanas más recientes en orden cronológico
        $last10Weeks = $allByWeek->take(10)->reverse();

        $labels = [];
        $values = [];

        foreach ($last10Weeks as $weekStart => $weekRecords) {
            $labels[] = Carbon::parse($weekStart)->format('d/m');
            $best     = $weekRecords->sortByDesc('fecha_registro')->first();
            $raw      = $best ? $best->{$this->metric} : null;
            $values[] = $raw !== null ? (float) $raw : null;
        }

        $this->chartData = [
            'labels' => $labels,
            'values' => $values,
            'color'  => $meta['color'],
            'unit'   => $meta['unit'],
            'hasData' => count(array_filter($values, fn($v) => $v !== null)) > 0,
        ];
    }

    public function render()
    {
        return view('livewire.grafico-progreso', [
            'metrics'       => self::$metrics,
            'currentMetric' => $this->metric,
            'metricLabel'   => self::$metrics[$this->metric]['label'],
        ]);
    }
}
