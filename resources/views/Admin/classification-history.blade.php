@extends('Admin.layouts.app')

@section('title', 'Riwayat Klasifikasi')

@section('page-title', 'Riwayat Klasifikasi')

@section('content')
<!-- Page Header -->
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-2">Riwayat Klasifikasi</h1>
    <p class="text-gray-600">Lihat semua riwayat klasifikasi tomat yang telah dilakukan</p>
</div>

<!-- Search and Filter Section -->
<div class="flex flex-col md:flex-row gap-4 items-start md:items-center justify-between mb-6">
    <!-- Search Bar -->
    <div class="relative flex-1 max-w-md">
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <i class="fas fa-search text-gray-400"></i>
        </div>
        <input type="text" 
               id="searchInput"
               placeholder="Cari riwayat…" 
               class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all">
    </div>
    
    <!-- Filter Buttons -->
    <div class="flex flex-wrap gap-2">
        <button class="filter-btn active px-4 py-2 rounded-full border border-gray-300 text-sm font-medium" data-filter="all">
            Semua
        </button>
        <button class="filter-btn px-4 py-2 rounded-full border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50" data-filter="mentah">
            Mentah
        </button>
        <button class="filter-btn px-4 py-2 rounded-full border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50" data-filter="setengah-matang">
            Setengah Matang
        </button>
        <button class="filter-btn px-4 py-2 rounded-full border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50" data-filter="matang">
            Matang
        </button>
    </div>
</div>

<!-- Classification Table -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Gambar
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Tanggal Unggah
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Klasifikasi
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Skor Keyakinan
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <!-- Sample Data Rows -->
                <tr class="table-row" data-classification="matang">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <img src="https://picsum.photos/seed/tomato1/50/50.jpg" 
                             alt="Tomato" 
                             class="w-12 h-12 rounded-full object-cover border-2 border-gray-200">
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        23 Nov 2024, 14:30
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="classification-badge px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-pink-100 text-pink-800">
                            Matang
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        <div class="flex items-center">
                            <span class="mr-2">95.2%</span>
                            <div class="w-16 bg-gray-200 rounded-full h-2">
                                <div class="bg-green-500 h-2 rounded-full" style="width: 95.2%"></div>
                            </div>
                        </div>
                    </td>
                </tr>
                
                <tr class="table-row" data-classification="setengah-matang">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <img src="https://picsum.photos/seed/tomato2/50/50.jpg" 
                             alt="Tomato" 
                             class="w-12 h-12 rounded-full object-cover border-2 border-gray-200">
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        23 Nov 2024, 13:45
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="classification-badge px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                            Setengah Matang
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        <div class="flex items-center">
                            <span class="mr-2">87.8%</span>
                            <div class="w-16 bg-gray-200 rounded-full h-2">
                                <div class="bg-yellow-500 h-2 rounded-full" style="width: 87.8%"></div>
                            </div>
                        </div>
                    </td>
                </tr>
                
                <tr class="table-row" data-classification="mentah">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <img src="https://picsum.photos/seed/tomato3/50/50.jpg" 
                             alt="Tomato" 
                             class="w-12 h-12 rounded-full object-cover border-2 border-gray-200">
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        23 Nov 2024, 12:20
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="classification-badge px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                            Mentah
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        <div class="flex items-center">
                            <span class="mr-2">92.1%</span>
                            <div class="w-16 bg-gray-200 rounded-full h-2">
                                <div class="bg-green-500 h-2 rounded-full" style="width: 92.1%"></div>
                            </div>
                        </div>
                    </td>
                </tr>
                
                <tr class="table-row" data-classification="matang">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <img src="https://picsum.photos/seed/tomato4/50/50.jpg" 
                             alt="Tomato" 
                             class="w-12 h-12 rounded-full object-cover border-2 border-gray-200">
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        23 Nov 2024, 11:15
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="classification-badge px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-pink-100 text-pink-800">
                            Matang
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        <div class="flex items-center">
                            <span class="mr-2">89.5%</span>
                            <div class="w-16 bg-gray-200 rounded-full h-2">
                                <div class="bg-green-500 h-2 rounded-full" style="width: 89.5%"></div>
                            </div>
                        </div>
                    </td>
                </tr>
                
                <tr class="table-row" data-classification="setengah-matang">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <img src="https://picsum.photos/seed/tomato5/50/50.jpg" 
                             alt="Tomato" 
                             class="w-12 h-12 rounded-full object-cover border-2 border-gray-200">
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        23 Nov 2024, 10:30
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="classification-badge px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                            Setengah Matang
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        <div class="flex items-center">
                            <span class="mr-2">91.3%</span>
                            <div class="w-16 bg-gray-200 rounded-full h-2">
                                <div class="bg-yellow-500 h-2 rounded-full" style="width: 91.3%"></div>
                            </div>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
        <div class="flex items-center justify-between">
            <div class="text-sm text-gray-700">
                Menampilkan <span class="font-medium">1</span> hingga <span class="font-medium">5</span> dari <span class="font-medium">24</span> hasil
            </div>
            
            <div class="flex items-center space-x-2">
                <button class="pagination-btn px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                    <i class="fas fa-chevron-left"></i>
                </button>
                
                <button class="pagination-btn active px-3 py-2 text-sm font-medium border border-gray-300 rounded-lg">
                    1
                </button>
                
                <button class="pagination-btn px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                    2
                </button>
                
                <button class="pagination-btn px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                    3
                </button>
                
                <button class="pagination-btn px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                    4
                </button>
                
                <button class="pagination-btn px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                    5
                </button>
                
                <button class="pagination-btn px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Filter button functionality
    document.querySelectorAll('.filter-btn').forEach(button => {
        button.addEventListener('click', function() {
            // Remove active class from all buttons
            document.querySelectorAll('.filter-btn').forEach(btn => {
                btn.classList.remove('active');
                btn.classList.add('bg-white', 'text-gray-700');
            });
            
            // Add active class to clicked button
            this.classList.add('active');
            this.classList.remove('bg-white', 'text-gray-700');
            
            // Filter table rows
            const filter = this.dataset.filter;
            const rows = document.querySelectorAll('.table-row');
            
            rows.forEach(row => {
                if (filter === 'all' || row.dataset.classification === filter) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    });
    
    // Pagination functionality
    document.querySelectorAll('.pagination-btn').forEach(button => {
        button.addEventListener('click', function() {
            // Only handle number buttons
            if (!this.innerHTML.includes('fa-chevron')) {
                // Remove active class from all pagination buttons
                document.querySelectorAll('.pagination-btn').forEach(btn => {
                    btn.classList.remove('active');
                    btn.classList.add('bg-white', 'text-gray-700');
                });
                
                // Add active class to clicked button
                this.classList.add('active');
                this.classList.remove('bg-white', 'text-gray-700');
            }
        });
    });
    
    // Search functionality
    const searchInput = document.getElementById('searchInput');
    searchInput.addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        const rows = document.querySelectorAll('.table-row');
        
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            if (text.includes(searchTerm)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
</script>
@endsection
