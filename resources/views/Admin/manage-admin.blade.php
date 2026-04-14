@extends('Admin.layouts.app')

@section('title', 'Kelola Akun Admin')

@section('page-title', 'Kelola Akun Admin')

@section('content')
<!-- Page Header -->
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-2">Kelola Akun Admin</h1>
    <p class="text-gray-600">Kelola admin yang memiliki akses ke sistem klasifikasi tomat</p>
</div>

<!-- Add Admin Button -->
<div class="mb-6">
    <button class="btn-primary px-6 py-3 bg-gradient-to-r from-red-500 to-red-600 text-white font-medium rounded-xl hover:from-red-600 hover:to-red-700 transition-all duration-300 flex items-center space-x-2">
        <i class="fas fa-plus"></i>
        <span>Tambah Admin</span>
    </button>
</div>

<!-- Admin Table -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Nama
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Email
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Role
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Action
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($admins as $admin)
                <tr class="table-row" data-id="{{ $admin->id }}">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $admin->name }}</div>
                        <div class="text-xs {{ $admin->email_verified_at ? 'text-green-500' : 'text-gray-500' }}">
                            {{ $admin->email_verified_at ? 'Active' : 'Inactive' }}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $admin->email }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @php
                            $roleClass = [
                                'admin' => 'bg-blue-100 text-blue-800'
                            ];
                            $roleLabel = [
                                'admin' => 'Admin'
                            ];
                        @endphp
                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $roleClass[$admin->role] ?? 'bg-gray-100 text-gray-800' }}">
                            {{ $roleLabel[$admin->role] ?? ucfirst($admin->role) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <button class="btn-action text-blue-600 hover:text-blue-900 mr-3 transition-colors cursor-pointer hover:bg-blue-50 p-2 rounded" 
                                onclick="editAdmin({{ $admin->id }})" 
                                title="Edit Admin">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn-action text-red-600 hover:text-red-900 transition-colors cursor-pointer hover:bg-red-50 p-2 rounded" 
                                onclick="deleteAdmin({{ $admin->id }})" 
                                title="Hapus Admin">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                        <div class="flex flex-col items-center">
                            <i class="fas fa-users text-4xl mb-2 text-gray-300"></i>
                            <span>Belum ada data admin</span>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('scripts')
<style>
.btn-action {
    transition: all 0.2s ease;
    transform: scale(1);
}

.btn-action:hover {
    transform: scale(1.1);
}

.btn-action:active {
    transform: scale(0.95);
}
</style>

<!-- Add Admin Modal -->
<div id="adminModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
    <div class="bg-white rounded-xl p-6 w-full max-w-md mx-4">
        <div class="flex justify-between items-center mb-4">
            <h3 id="modalTitle" class="text-xl font-semibold text-gray-900">Tambah Admin</h3>
            <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <form id="adminForm" onsubmit="saveAdmin(event)">
            @csrf
            <input type="hidden" id="adminId" name="id">
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Nama</label>
                <input type="text" id="name" name="name" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                <input type="email" id="email" name="email" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                <input type="password" id="password" name="password" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500"
                       placeholder="Kosongkan jika tidak ingin mengubah password">
            </div>
            
            <!-- Role field removed - always 'admin' -->
            <input type="hidden" name="role" value="admin">
            
            <div class="flex justify-end space-x-3">
                <button type="button" onclick="closeModal()" 
                        class="px-4 py-2 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 transition-colors">
                    Batal
                </button>
                <button type="submit" onclick="console.log('Submit button clicked');"
                        class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    let currentEditId = null;

    // Add Admin Modal functionality
    document.addEventListener('DOMContentLoaded', function() {
        console.log('DOM loaded, setting up admin modal...');
        
        const addAdminBtn = document.querySelector('.btn-primary');
        const adminForm = document.getElementById('adminForm');
        
        if (addAdminBtn) {
            console.log('Add admin button found');
            addAdminBtn.addEventListener('click', function() {
                console.log('Add admin button clicked');
                openModal();
            });
        } else {
            console.error('Add admin button not found');
        }
        
        if (adminForm) {
            console.log('Admin form found, setting up submit handler');
            adminForm.addEventListener('submit', function(e) {
                console.log('Form submit event triggered');
                saveAdmin(e);
            });
        } else {
            console.error('Admin form not found');
        }
        
        // Setup action buttons
        const editButtons = document.querySelectorAll('[onclick*="editAdmin"]');
        const deleteButtons = document.querySelectorAll('[onclick*="deleteAdmin"]');
        
        console.log('Action buttons found:', {
            edit: editButtons.length,
            delete: deleteButtons.length
        });
    });

    function openModal(adminId = null) {
        const modal = document.getElementById('adminModal');
        const form = document.getElementById('adminForm');
        const title = document.getElementById('modalTitle');
        
        form.reset();
        document.getElementById('adminId').value = adminId || '';
        
        if (adminId) {
            title.textContent = 'Edit Admin';
            // Load admin data
            fetch(`/admin/manage-admin/${adminId}/edit`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('name').value = data.name;
                    document.getElementById('email').value = data.email;
                    document.getElementById('role').value = data.role;
                    document.getElementById('password').removeAttribute('required');
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Gagal memuat data admin');
                });
        } else {
            title.textContent = 'Tambah Admin';
            document.getElementById('password').setAttribute('required', 'required');
        }
        
        modal.classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('adminModal').classList.add('hidden');
        document.getElementById('adminForm').reset();
    }

    function saveAdmin(event) {
        event.preventDefault();
        
        const formData = new FormData(event.target);
        const adminId = formData.get('id');
        const submitBtn = event.target.querySelector('button[type="submit"]');
        
        // Show loading state
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Menyimpan...';
        submitBtn.disabled = true;
        
        const url = adminId ? `/admin/manage-admin/${adminId}` : '/admin/manage-admin';
        const method = adminId ? 'PUT' : 'POST';
        
        // Convert FormData to object for JSON
        const data = Object.fromEntries(formData.entries());
        console.log('Data being sent:', data); // Debug log
        delete data.id; // Remove id from data
        
        fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(data)
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.errors) {
                // Handle validation errors
                let errorMessages = '';
                for (const field in data.errors) {
                    errorMessages += data.errors[field].join('\n') + '\n';
                }
                alert('Validation Error:\n' + errorMessages);
            } else {
                alert(data.success || 'Admin berhasil disimpan!');
                closeModal();
                location.reload(); // Reload to show updated data
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan. Silakan coba lagi.\nError: ' + error.message);
        })
        .finally(() => {
            // Reset button state
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        });
    }

    function editAdmin(id) {
        console.log('Edit admin clicked for ID:', id);
        openModal(id);
    }

    function deleteAdmin(id) {
        console.log('Delete admin clicked for ID:', id);
        if (confirm('Apakah Anda yakin ingin menghapus admin ini?')) {
            fetch(`/admin/manage-admin/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    alert(data.error);
                } else {
                    location.reload();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan. Silakan coba lagi.');
            });
        }
    }
</script>
@endsection
