<!-- Sidebar Component -->
<aside class="w-64 bg-white border-r border-gray-200 h-full flex flex-col">
    <!-- Logo Section -->
    <div class="p-6 border-b border-gray-200">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 bg-gradient-to-br from-red-400 to-red-600 rounded-lg flex items-center justify-center">
                <i class="fas fa-robot text-white text-lg"></i>
            </div>
            <h1 class="text-xl font-bold text-gray-800">TomatoScan</h1>
        </div>
    </div>
    
    <!-- Navigation Menu -->
    <nav class="flex-1 p-4 space-y-2">
        <a href="{{ route('admin.dashboard') }}" 
           class="sidebar-link flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-gray-50 transition-all {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="fas fa-home w-5"></i>
            <span>Dashboard</span>
        </a>
        
        <a href="{{ route('admin.manage-admin') }}" 
           class="sidebar-link flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-gray-50 transition-all {{ request()->routeIs('admin.manage-admin') ? 'active' : '' }}">
            <i class="fas fa-users w-5"></i>
            <span>Kelola Akun Admin</span>
        </a>
        
        <a href="{{ route('admin.classification-history') }}" 
           class="sidebar-link flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-gray-50 transition-all {{ request()->routeIs('admin.classification-history') ? 'active' : '' }}">
            <i class="fas fa-history w-5"></i>
            <span>Riwayat Klasifikasi</span>
        </a>
        
        <a href="{{ route('admin.system-statistics') }}" 
           class="sidebar-link flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-gray-50 transition-all {{ request()->routeIs('admin.system-statistics') ? 'active' : '' }}">
            <i class="fas fa-chart-bar w-5"></i>
            <span>Statistik Sistem</span>
        </a>
    </nav>
    
    <!-- User Profile Section -->
    <div class="p-4 border-t border-gray-200">
        <div class="flex items-center space-x-3 px-4 py-3">
            <div class="w-8 h-8 bg-gradient-to-br from-red-400 to-pink-400 rounded-full flex items-center justify-center">
                <i class="fas fa-user text-white text-sm"></i>
            </div>
            <div class="flex-1">
                <p class="text-sm font-medium text-gray-900">{{ session('admin_name', 'Admin') }}</p>
                <p class="text-xs text-gray-500">Administrator</p>
            </div>
        </div>
    </div>
    
    <!-- Logout Section -->
    <div class="p-4 border-t border-gray-200">
        <a href="{{ route('admin.logout') }}" 
           class="sidebar-item flex items-center space-x-3 px-4 py-3 rounded-lg text-red-600 hover:bg-red-50 transition-all">
            <i class="fas fa-sign-out-alt w-5"></i>
            <span>Logout</span>
        </a>
    </div>
    
    <!-- Footer -->
    <div class="p-4 text-center text-xs text-gray-500 border-t border-gray-200">
        Made with <span class="text-red-500">❤️</span> by TomatoScan Team
    </div>
</aside>

<style>
    .sidebar-item {
        transition: all 0.3s ease;
    }
    
    .sidebar-item:hover {
        transform: translateX(5px);
    }
    
    .sidebar-item.active {
        background: linear-gradient(90deg, #fee2e2 0%, #fef2f2 100%);
        border-left: 4px solid #ef4444;
        color: #ef4444;
        font-weight: 500;
    }
</style>
