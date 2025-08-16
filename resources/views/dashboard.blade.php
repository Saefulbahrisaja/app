@extends('layouts.app')
@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header Section -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Dashboard Kas Produksi</h1>
        <p class="text-gray-600">Ringkasan keuangan dan produksi terkini</p>
    </div>

    <!-- 4 Main Cards with Icons -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Saldo Card -->
        <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl shadow-lg p-6 border border-blue-200 hover:shadow-xl transition-shadow duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-600 text-sm font-medium mb-1">Saldo Kas</p>
                    <p class="text-3sm font-bold text-blue-900">
                        Rp {{ number_format($kasMasuk - $kasKeluar, 0, ',', '.') }}
                    </p>
                </div>
                <div class="bg-blue-500 p-3 rounded-full">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <span class="text-xs text-blue-600 bg-blue-100 px-2 py-1 rounded-full">Real-time</span>
            </div>
        </div>

        <!-- Pemasukan Card -->
        <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl shadow-lg p-6 border border-green-200 hover:shadow-xl transition-shadow duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-600 text-sm font-medium mb-1">Total Pemasukan</p>
                    <p class="text-3sm font-bold text-green-900">
                        Rp {{ number_format($kasMasuk, 0, ',', '.') }}
                    </p>
                </div>
                <div class="bg-green-500 p-3 rounded-full">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <span class="text-xs text-green-600 bg-green-100 px-2 py-1 rounded-full">Bulan ini</span>
            </div>
        </div>

        <!-- Pengeluaran Card -->
        <div class="bg-gradient-to-br from-red-50 to-red-100 rounded-xl shadow-lg p-6 border border-red-200 hover:shadow-xl transition-shadow duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-red-600 text-sm font-medium mb-1">Total Pengeluaran</p>
                    <p class="text-3sm font-bold text-red-900">
                        Rp {{ number_format($kasKeluar, 0, ',', '.') }}
                    </p>
                </div>
                <div class="bg-red-500 p-3 rounded-full">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <span class="text-xs text-red-600 bg-red-100 px-2 py-1 rounded-full">Bulan ini</span>
            </div>
        </div>

        <!-- Produksi Card -->
        <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-xl shadow-lg p-6 border border-yellow-200 hover:shadow-xl transition-shadow duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-yellow-600 text-sm font-medium mb-1">Total Produksi</p>
                    <p class="text-3sm font-bold text-yellow-900">
                        {{ number_format($produksi, 0, ',', '.') }}
                    </p>
                </div>
                <div class="bg-yellow-500 p-3 rounded-full">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <span class="text-xs text-yellow-600 bg-yellow-100 px-2 py-1 rounded-full">Unit</span>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Line Chart Card -->
        <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-shadow duration-300">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Analisis Biaya & HPP</h3>
                <div class="bg-blue-100 p-2 rounded-lg">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
            </div>
            <div class="relative">
                <canvas id="biayaChart" class="h-[350px] w-full"></canvas>
            </div>
        </div>

        <!-- Bar Chart Card -->
        <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-shadow duration-300">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Performa Produk</h3>
                <div class="bg-green-100 p-2 rounded-lg">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                </div>
            </div>
            <div class="relative">
                <canvas id="marginChart" class="h-[350px] w-full"></canvas>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Enhanced currency formatter
    function formatRupiahShort(value) {
        if (value >= 1_000_000_000) {
            return 'Rp ' + (value / 1_000_000_000).toFixed(1) + 'M';
        } else if (value >= 1_000_000) {
            return 'Rp ' + (value / 1_000_000).toFixed(1) + 'JT';
        } else if (value >= 1_000) {
            return 'Rp ' + (value / 1_000).toFixed(1) + 'RB';
        } else {
            return 'Rp ' + value.toLocaleString('id-ID');
        }
    }

    // Chart.js default settings
    Chart.defaults.font.family = '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif';
    Chart.defaults.color = '#374151';

    fetch('{{ route("dashboard.chart") }}')
        .then(res => res.json())
        .then(data => {
            // Enhanced Line Chart
            const biayaCtx = document.getElementById('biayaChart').getContext('2d');
            new Chart(biayaCtx, {
                type: 'line',
                data: {
                    labels: data.labels,
                    datasets: [
                        {
                            label: 'Total Biaya',
                            data: data.total_biaya,
                            borderColor: '#3b82f6',
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            tension: 0.4,
                            fill: true,
                            pointBackgroundColor: '#3b82f6',
                            pointBorderColor: '#ffffff',
                            pointBorderWidth: 2,
                            pointRadius: 4,
                            pointHoverRadius: 6
                        },
                        {
                            label: 'HPP',
                            data: data.hpp,
                            borderColor: '#10b981',
                            backgroundColor: 'rgba(16, 185, 129, 0.1)',
                            tension: 0.4,
                            fill: true,
                            pointBackgroundColor: '#10b981',
                            pointBorderColor: '#ffffff',
                            pointBorderWidth: 2,
                            pointRadius: 4,
                            pointHoverRadius: 6
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                usePointStyle: true,
                                padding: 20
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            titleColor: '#ffffff',
                            bodyColor: '#ffffff',
                            cornerRadius: 8,
                            callbacks: {
                                label: function (context) {
                                    return context.dataset.label + ': ' + formatRupiahShort(context.raw);
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false
                            }
                        },
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: '#f3f4f6'
                            },
                            ticks: {
                                callback: function (value) {
                                    return formatRupiahShort(value);
                                }
                            }
                        }
                    }
                }
            });

            // Enhanced Bar Chart
            const marginCtx = document.getElementById('marginChart').getContext('2d');
            new Chart(marginCtx, {
                type: 'bar',
                data: {
                    labels: data.products,
                    datasets: [
                        {
                            label: 'Profit',
                            data: data.margins,
                            backgroundColor: 'rgba(245, 158, 11, 0.8)',
                            borderColor: '#d97706',
                            borderWidth: 0,
                            borderRadius: 4,
                            yAxisID: 'y1'
                        },
                        {
                            label: 'Produk Terjual',
                            data: data.terjuals,
                            backgroundColor: 'rgba(16, 185, 129, 0.8)',
                            borderColor: '#059669',
                            borderWidth: 0,
                            borderRadius: 4,
                            yAxisID: 'y'
                        },
                        {
                            label: 'Stok Tersisa',
                            data: data.stoks,
                            backgroundColor: 'rgba(59, 130, 246, 0.8)',
                            borderColor: '#2563eb',
                            borderWidth: 0,
                            borderRadius: 4,
                            yAxisID: 'y'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        mode: 'index',
                        intersect: false
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false
                            }
                        },
                        y: {
                            beginAtZero: true,
                            position: 'left',
                            grid: {
                                color: '#f3f4f6'
                            },
                            title: {
                                display: true,
                                text: 'Jumlah (pcs)',
                                font: {
                                    size: 12
                                }
                            }
                        },
                        y1: {
                            beginAtZero: true,
                            position: 'right',
                            grid: {
                                drawOnChartArea: false
                            },
                            title: {
                                display: true,
                                text: 'Profit (Rp)',
                                font: {
                                    size: 12
                                }
                            },
                            ticks: {
                                callback: function (value) {
                                    return formatRupiahShort(value);
                                }
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                usePointStyle: true,
                                padding: 20
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            titleColor: '#ffffff',
                            bodyColor: '#ffffff',
                            cornerRadius: 8,
                            callbacks: {
                                label: function (context) {
                                    if (context.dataset.label === 'Profit') {
                                        return context.dataset.label + ': ' + formatRupiahShort(context.raw);
                                    } else {
                                        return context.dataset.label + ': ' + context.raw.toLocaleString('id-ID') + ' pcs';
                                    }
                                }
                            }
                        }
                    }
                }
            });
        });
});
</script>
@endpush
