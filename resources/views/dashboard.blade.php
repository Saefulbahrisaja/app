@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
	<h2 class="text-2xl font-bold text-gray-800 mb-6">Dashboard Kas Produksi (kas Pro)</h2>

	<!-- 4 Main Cards -->
	<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
		<div class="bg-white rounded-lg shadow p-5 flex flex-col items-center">
			<span class="text-gray-500 text-sm mb-2">Saldo</span>
			<span class="text-2xl font-bold text-blue-600">
				Rp {{ number_format($kasMasuk - $kasKeluar, 0, ',', '.') }}
			</span>
		</div>
		<div class="bg-white rounded-lg shadow p-5 flex flex-col items-center">
			<span class="text-gray-500 text-sm mb-2">Pemasukan</span>
			<span class="text-2xl font-bold text-green-600">
				Rp {{ number_format($kasMasuk, 0, ',', '.') }}
			</span>
		</div>
		<div class="bg-white rounded-lg shadow p-5 flex flex-col items-center">
			<span class="text-gray-500 text-sm mb-2">Pengeluaran</span>
			<span class="text-2xl font-bold text-red-600">
				Rp {{ number_format($kasKeluar, 0, ',', '.') }}
			</span>
		</div>
		<div class="bg-white rounded-lg shadow p-5 flex flex-col items-center">
			<span class="text-gray-500 text-sm mb-2">Total Produksi</span>
			<span class="text-2xl font-bold text-yellow-600">
				{{ number_format($produksi, 0, ',', '.') }}
			</span>
		</div>
	</div>

<!-- 2 Grafik Besar: Biaya & Produk -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">

    <!-- Grafik Garis: Total Biaya & HPP -->
    <div class="bg-white rounded-lg shadow p-5">
        <h4 class="text-lg font-semibold text-gray-800 mb-3">Grafik Total Biaya & HPP</h4>
        <canvas id="biayaChart" class="h-[400px] w-full"></canvas>
    </div>

    <!-- Grafik Horizontal: Profit, Produk Terjual, dan Stok -->
    <div class="bg-white rounded-lg shadow p-5">
        <h4 class="text-lg font-semibold text-gray-800 mb-3">Grafik Profit, Produk Terjual, dan Stok</h4>
        <canvas id="marginChart" class="h-[500px] w-full"></canvas>
    </div>

</div>


</div>
@endsection
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    // Fungsi untuk mempersingkat format Rupiah (K, M, B)
    function formatRupiahShort(value) {
        if (value >= 1_000_000_000) {
            return 'Rp ' + (value / 1_000_000_000).toFixed(1) + 'miliar';
        } else if (value >= 1_000_000) {
            return 'Rp ' + (value / 1_000_000).toFixed(1) + 'jt';
        } else if (value >= 1_000) {
            return 'Rp ' + (value / 1_000).toFixed(1) + 'rb';
        } else {
            return 'Rp ' + value;
        }
    }

    fetch('{{ route("dashboard.chart") }}')
        .then(res => res.json())
        .then(data => {

            // Grafik Line: Biaya vs HPP
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
                            backgroundColor: 'rgba(59, 130, 246, 0.2)',
                            tension: 0.3,
                        },
                        {
                            label: 'HPP',
                            data: data.hpp,
                            borderColor: '#10b981',
                            backgroundColor: 'rgba(16, 185, 129, 0.2)',
                            tension: 0.3,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        tooltip: {
                            callbacks: {
                                label: function (context) {
                                    return context.dataset.label + ': ' + formatRupiahShort(context.raw);
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function (value) {
                                    return formatRupiahShort(value);
                                }
                            }
                        }
                    }
                }
            });

            // Grafik Bar: Margin, Terjual, dan Stok per Produk
            const marginCtx = document.getElementById('marginChart').getContext('2d');
            new Chart(marginCtx, {
                type: 'bar',
                data: {
                    labels: data.products,
                    datasets: [
                        {
                            label: 'Profit',
                            data: data.margins,
                            backgroundColor: '#f59e0b',
                            borderColor: '#d97706',
                            borderWidth: 1,
                            yAxisID: 'y1'
                        },
                        {
                            label: 'Produk Terjual',
                            data: data.terjuals,
                            backgroundColor: '#10b981',
                            borderColor: '#059669',
                            borderWidth: 1,
                            yAxisID: 'y'
                        },
                        {
                            label: 'Stok Tersisa',
                            data: data.stoks,
                            backgroundColor: '#3b82f6',
                            borderColor: '#2563eb',
                            borderWidth: 1,
                            yAxisID: 'y'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    interaction: {
                        mode: 'index',
                        intersect: false
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            position: 'left',
                            title: {
                                display: true,
                                text: 'Qty (pcs)'
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
                                text: 'Profit (Rp)'
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
                            position: 'top'
                        },
                        tooltip: {
                            callbacks: {
                                label: function (context) {
                                    if (context.dataset.label === 'Profit') {
                                        return context.dataset.label + ': ' + formatRupiahShort(context.raw);
                                    } else {
                                        return context.dataset.label + ': ' + context.raw + ' pcs';
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
