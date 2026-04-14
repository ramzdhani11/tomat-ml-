@extends('Admin.layouts.app')

@section('title', 'Dashboard')

@section('page-title', 'Dashboard')

@section('content')
<!-- Page Header -->
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-2">Dashboard</h1>
    <p class="text-gray-600">Selamat datang di dashboard sistem klasifikasi tomat</p>
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
        <h3 class="text-2xl font-bold text-gray-900 mb-1">12,500</h3>
        <p class="text-sm text-gray-600">Total Klasifikasi</p>
    </div>
    
    <!-- Akurasi Sistem Card -->
    <div class="stat-card bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                <i class="fas fa-bullseye text-purple-600 text-xl"></i>
            </div>
            <span class="text-xs text-green-600 font-medium bg-green-50 px-2 py-1 rounded-full">
                +2.1%
            </span>
        </div>
        <h3 class="text-2xl font-bold text-gray-900 mb-1">95.2%</h3>
        <p class="text-sm text-gray-600">Akurasi Sistem</p>
    </div>
    
    <!-- Admin Aktif Card -->
    <div class="stat-card bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center">
                <i class="fas fa-users text-orange-600 text-xl"></i>
            </div>
            <span class="text-xs bg-orange-100 text-orange-800 font-medium px-3 py-1 rounded-full">
                {{ App\Models\User::where('role', 'admin')->count() }}
            </span>
        </div>
        <h3 class="text-lg font-bold text-gray-900 mb-1">Admin Aktif</h3>
        <p class="text-sm text-gray-600">Total admin terdaftar</p>
    </div>
    
    <!-- Data Hari Ini Card -->
    <div class="stat-card bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                <i class="fas fa-calendar-day text-green-600 text-xl"></i>
            </div>
            <span class="text-xs text-green-600 font-medium bg-green-50 px-2 py-1 rounded-full">
                +8.2%
            </span>
        </div>
        <h3 class="text-2xl font-bold text-gray-900 mb-1">230</h3>
        <p class="text-sm text-gray-600">Data Hari Ini</p>
    </div>
</div>

<!-- Charts Section -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Trend Chart -->
    <div class="chart-card bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
        <div class="mb-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-2">Trend Klasifikasi</h2>
            <p class="text-sm text-gray-600">Jumlah klasifikasi per hari dalam seminggu terakhir</p>
        </div>
        <div class="chart-container">
            <canvas id="trendChart"></canvas>
        </div>
    </div>
    
    <!-- Classification Distribution -->
    <div class="chart-card bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
        <div class="mb-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-2">Distribusi Klasifikasi</h2>
            <p class="text-sm text-gray-600">Persentase kategori kematangan tomat</p>
        </div>
        <div class="chart-container">
            <canvas id="distributionChart"></canvas>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Trend Chart
    const trendCtx = document.getElementById('trendChart').getContext('2d');
    new Chart(trendCtx, {
        type: 'line',
        data: {
            labels: ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'],
            datasets: [{
                label: 'Jumlah Klasifikasi',
                data: [180, 220, 195, 240, 210, 185, 230],
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
    
    // Distribution Chart
    const distributionCtx = document.getElementById('distributionChart').getContext('2d');
    new Chart(distributionCtx, {
        type: 'doughnut',
        data: {
            labels: ['Mentah', 'Setengah Matang', 'Matang'],
            datasets: [{
                data: [35, 40, 25],
                backgroundColor: [
                    'rgba(34, 197, 94, 0.8)',
                    'rgba(249, 115, 22, 0.8)',
                    'rgba(236, 72, 153, 0.8)'
                ],
                borderColor: [
                    'rgba(34, 197, 94, 1)',
                    'rgba(249, 115, 22, 1)',
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
                    position: 'bottom'
                }
            },
            cutout: '70%'
        }
    });
</script>
@endsection
