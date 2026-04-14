@extends('Admin.layouts.app')

@section('title', 'Statistik Sistem')

@section('page-title', 'Statistik Sistem')

@section('content')
<!-- Page Header -->
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-2">Statistik Sistem</h1>
    <p class="text-gray-600">Pantau performa dan statistik sistem klasifikasi tomat</p>
</div>

<!-- Summary Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Total Klasifikasi Card -->
    <div class="stat-card bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                <i class="fas fa-chart-bar text-blue-600 text-xl"></i>
            </div>
            <span class="text-xs text-green-600 font-medium bg-green-50 px-2 py-1 rounded-full">
                +12.5%
            </span>
        </div>
        <h3 class="text-2xl font-bold text-gray-900 mb-1">8,456</h3>
        <p class="text-sm text-gray-600">Total Klasifikasi</p>
    </div>
    
    <!-- Klasifikasi Hari Ini Card -->
    <div class="stat-card bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                <i class="fas fa-calendar-day text-green-600 text-xl"></i>
            </div>
            <span class="text-xs text-green-600 font-medium bg-green-50 px-2 py-1 rounded-full">
                +8.2%
            </span>
        </div>
        <h3 class="text-2xl font-bold text-gray-900 mb-1">142</h3>
        <p class="text-sm text-gray-600">Klasifikasi Hari Ini</p>
    </div>
    
    <!-- Akurasi Rata-rata Card -->
    <div class="stat-card bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                <i class="fas fa-bullseye text-purple-600 text-xl"></i>
            </div>
            <span class="text-xs text-green-600 font-medium bg-green-50 px-2 py-1 rounded-full">
                +2.1%
            </span>
        </div>
        <h3 class="text-2xl font-bold text-gray-900 mb-1">94.8%</h3>
        <p class="text-sm text-gray-600">Akurasi Rata-rata</p>
    </div>
    
    <!-- Pengguna Aktif Admin Card -->
    <div class="stat-card bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center">
                <i class="fas fa-users text-orange-600 text-xl"></i>
            </div>
            <span class="text-xs text-gray-600 font-medium bg-gray-50 px-2 py-1 rounded-full">
                0%
            </span>
        </div>
        <h3 class="text-2xl font-bold text-gray-900 mb-1">12</h3>
        <p class="text-sm text-gray-600">Pengguna Aktif Admin</p>
    </div>
</div>

<!-- Charts Section -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <!-- Bar Chart - Jumlah Klasifikasi Harian -->
    <div class="chart-card bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
        <div class="mb-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-2">Jumlah Klasifikasi Harian</h2>
            <p class="text-sm text-gray-600">Statistik klasifikasi per hari dalam 7 hari terakhir</p>
        </div>
        <div class="chart-container">
            <canvas id="barChart"></canvas>
        </div>
    </div>
    
    <!-- Line Chart - Tren Klasifikasi Bulanan -->
    <div class="chart-card bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
        <div class="mb-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-2">Tren Klasifikasi Bulanan</h2>
            <p class="text-sm text-gray-600">Perkembangan klasifikasi per bulan</p>
        </div>
        <div class="chart-container">
            <canvas id="lineChart"></canvas>
        </div>
    </div>
</div>

<!-- Donut Chart Section -->
<div class="chart-card bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
    <div class="mb-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-2">Distribusi Kematangan Tomat</h2>
        <p class="text-sm text-gray-600">Persentase hasil klasifikasi berdasarkan kategori kematangan</p>
    </div>
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <div class="chart-container">
            <canvas id="donutChart"></canvas>
        </div>
        <div class="flex items-center justify-center">
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-4 h-4 bg-green-500 rounded-full mr-3"></div>
                        <span class="text-sm text-gray-700">Mentah</span>
                    </div>
                    <span class="text-sm font-medium text-gray-900">35%</span>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-4 h-4 bg-yellow-500 rounded-full mr-3"></div>
                        <span class="text-sm text-gray-700">Setengah Matang</span>
                    </div>
                    <span class="text-sm font-medium text-gray-900">40%</span>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-4 h-4 bg-pink-500 rounded-full mr-3"></div>
                        <span class="text-sm text-gray-700">Matang</span>
                    </div>
                    <span class="text-sm font-medium text-gray-900">25%</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Bar Chart - Jumlah Klasifikasi Harian
    const barCtx = document.getElementById('barChart').getContext('2d');
    new Chart(barCtx, {
        type: 'bar',
        data: {
            labels: ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'],
            datasets: [{
                label: 'Jumlah Klasifikasi',
                data: [145, 189, 167, 198, 234, 178, 156],
                backgroundColor: 'rgba(239, 68, 68, 0.8)',
                borderColor: 'rgba(239, 68, 68, 1)',
                borderWidth: 2,
                borderRadius: 8,
                barThickness: 40
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)',
                        drawBorder: false
                    }
                },
                x: {
                    grid: {
                        display: false,
                        drawBorder: false
                    }
                }
            }
        }
    });
    
    // Line Chart - Tren Klasifikasi Bulanan
    const lineCtx = document.getElementById('lineChart').getContext('2d');
    new Chart(lineCtx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
            datasets: [{
                label: 'Jumlah Klasifikasi',
                data: [1200, 1350, 1100, 1450, 1600, 1750, 1900, 1850, 2000, 2100, 1950, 2200],
                borderColor: 'rgba(239, 68, 68, 1)',
                backgroundColor: 'rgba(239, 68, 68, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: 'rgba(239, 68, 68, 1)',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 6,
                pointHoverRadius: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)',
                        drawBorder: false
                    }
                },
                x: {
                    grid: {
                        display: false,
                        drawBorder: false
                    }
                }
            }
        }
    });
    
    // Donut Chart - Distribusi Kematangan Tomat
    const donutCtx = document.getElementById('donutChart').getContext('2d');
    new Chart(donutCtx, {
        type: 'doughnut',
        data: {
            labels: ['Mentah', 'Setengah Matang', 'Matang'],
            datasets: [{
                data: [35, 40, 25],
                backgroundColor: [
                    'rgba(34, 197, 94, 0.8)',
                    'rgba(250, 204, 21, 0.8)',
                    'rgba(236, 72, 153, 0.8)'
                ],
                borderColor: [
                    'rgba(34, 197, 94, 1)',
                    'rgba(250, 204, 21, 1)',
                    'rgba(236, 72, 153, 1)'
                ],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            cutout: '70%'
        }
    });
</script>
@endsection
