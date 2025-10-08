<?php

namespace App\Filament\Widgets;

use App\Models\RekapHarian;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Filament\Widgets\ChartWidget;
use Filament\Support\RawJs;
use Carbon\Carbon;

class RevenueChart extends ChartWidget
{
    protected ?string $heading = 'Trend Omzet vs Pengeluaran';
    protected ?string $description = 'Perbandingan dan perhitungan laba bersih berdasarkan periode.';

    protected int|string|array $columnSpan = 'full';
    protected static ?int $sort = 2;

    public ?string $filter = '30d';

    // Tambahkan properti untuk maxHeight (filament ChartWidget mendukung ini) :contentReference[oaicite:0]{index=0}
    protected ?string $maxHeight = '800px';  // misalnya 400px, bisa kamu ubah sesuai kebutuhan

    protected function getData(): array
    {
        $dateRange = match ($this->filter) {
            '7d' => [
                'start' => now()->subDays(7)->startOfDay(),
                'end' => now()->endOfDay(),
                'interval' => 'perDay'
            ],
            '30d' => [
                'start' => now()->subDays(30)->startOfDay(),
                'end' => now()->endOfDay(),
                'interval' => 'perDay'
            ],
            '12m' => [
                'start' => now()->subMonths(12)->startOfMonth(),
                'end' => now()->endOfMonth(),
                'interval' => 'perMonth'
            ],
            default => [
                'start' => now()->subDays(30)->startOfDay(),
                'end' => now()->endOfDay(),
                'interval' => 'perDay'
            ]
        };

        $dataOmzet = Trend::model(RekapHarian::class)
                    ->between(
                        start: $dateRange['start'],
                        end: $dateRange['end'],
                    )
            ->{$dateRange['interval']}()
                ->sum('total_omzet');

        $dataPengeluaran = Trend::model(RekapHarian::class)
                    ->between(
                        start: $dateRange['start'],
                        end: $dateRange['end'],
                    )
            ->{$dateRange['interval']}()
                ->sum('total_pengeluaran');

        $labels = $dataOmzet->map(function (TrendValue $value) use ($dateRange) {
            $date = $value->date;
            if (is_string($date)) {
                $date = Carbon::parse($date);
            }
            return $dateRange['interval'] === 'perMonth'
                ? $date->format('M Y')
                : $date->format('d M');
        });

        $labaData = [];
        foreach ($dataOmzet as $index => $value) {
            $omzet = $value->aggregate ?? 0;
            $pengeluaran = $dataPengeluaran[$index]->aggregate ?? 0;
            $labaData[] = $omzet - $pengeluaran;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Total Omzet',
                    'data' => $dataOmzet->map(fn(TrendValue $value) => $value->aggregate),
                    'borderColor' => '#10B981',
                    'backgroundColor' => 'rgba(16, 185, 129, 0.1)',
                    'fill' => false,
                    'tension' => 0.4,
                    'borderWidth' => 3,
                ],
                [
                    'label' => 'Total Pengeluaran',
                    'data' => $dataPengeluaran->map(fn(TrendValue $value) => $value->aggregate),
                    'borderColor' => '#EF4444',
                    'backgroundColor' => 'rgba(239, 68, 68, 0.1)',
                    'fill' => false,
                    'tension' => 0.4,
                    'borderWidth' => 3,
                ],
                [
                    'label' => 'Laba / Rugi',
                    'data' => $labaData,
                    'borderColor' => '#3B82F6',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.2)',
                    'fill' => '+1',
                    'tension' => 0.4,
                    'pointStyle' => 'circle',
                    'pointRadius' => 6,
                    'pointHoverRadius' => 8,
                    'borderWidth' => 3,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getFilters(): ?array
    {
        return [
            '7d' => '7 Hari Terakhir',
            '30d' => '30 Hari Terakhir',
            '12m' => '1 Tahun Terakhir',
        ];
    }

    protected function getOptions(): RawJs
    {
        return RawJs::make(<<<JS
        {
            plugins: {
                tooltip: {
                    mode: 'index',
                    intersect: false,
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            if (label) {
                                label += ': ';
                            }
                            label += 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                            return label;
                        }
                    },
                    padding: 12,
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleFont: { size: 14 },
                    bodyFont: { size: 13 },
                },
                legend: {
                    display: true,
                   position: 'top',
                    align: 'center',
                    labels: {
                        usePointStyle: true,
                        pointStyle: 'circle',
                        font: { size: 13 }
                    },
                    layout: {
                        padding: {
                            bottom: 20
                        }
                    }
                }
            },
            scales: {
                y: {
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + value.toLocaleString('id-ID');
                        },
                        padding: 10,
                    },
                    grid: {
                        drawBorder: false,
                    },
                    padding: {
                        top: 10,
                        bottom: 10
                    }
                },
                x: {
                    ticks: {
                        padding: 10,
                    },
                    grid: {
                        drawBorder: false,
                    },
                    padding: {
                        left: 10,
                        right: 10
                    }
                }
            },
            interaction: {
                intersect: false,
                mode: 'nearest',
            },
            layout: {
                padding: {
                    left: 30,
                    right: 30,
                    top: 30,
                    bottom: 50  // tambahkan lebih untuk ruang bawah
                }
            },
            elements: {
                point: {
                    hoverBackgroundColor: '#ffffff',
                    hoverBorderWidth: 3,
                }
            }
        }
        JS);
    }

    protected ?string $pollingInterval = '30s';
}
