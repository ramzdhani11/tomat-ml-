<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    public function index()
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login')->with('error', 'Silakan login terlebih dahulu.');
        }
        
        // Ambil semua admin dengan role 'admin'
        $admins = User::where('role', 'admin')->orderBy('created_at', 'desc')->get();
        
        return view('Admin.manage-admin', compact('admins'));
    }

    public function store(Request $request)
    {
        if (!session('admin_logged_in')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Validate input - role tidak termasuk, otomatis 'admin'
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Create admin dengan role selalu 'admin', tidak dari input
        $admin = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'admin',  // Role SELALU 'admin'
            'email_verified_at' => now()
        ]);

        return response()->json(['success' => 'Admin berhasil ditambahkan', 'admin' => $admin]);
    }

    public function edit($id)
    {
        if (!session('admin_logged_in')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $admin = User::findOrFail($id);
        return response()->json($admin);
    }

    public function update(Request $request, $id)
    {
        if (!session('admin_logged_in')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $admin = User::findOrFail($id);
        
        // Pastikan hanya admin yang dapat diupdate
        if ($admin->role !== 'admin') {
            return response()->json(['error' => 'Hanya admin yang dapat dikelola'], 422);
        }

        // Validate input - role tidak termasuk, tidak boleh diubah
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$id,
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Update hanya name dan email, role TIDAK boleh diubah
        $admin->update([
            'name' => $request->name,
            'email' => $request->email,
            // Role selalu 'admin', tidak dapat diubah
        ]);

        if ($request->filled('password')) {
            $admin->update(['password' => Hash::make($request->password)]);
        }

        return response()->json(['success' => 'Admin berhasil diperbarui', 'admin' => $admin]);
    }

    public function destroy($id)
    {
        if (!session('admin_logged_in')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $admin = User::findOrFail($id);
        
        // Pastikan hanya admin yang dihapus
        if ($admin->role !== 'admin') {
            return response()->json(['error' => 'Hanya admin yang dapat dihapus'], 422);
        }
        
        // Prevent deleting the currently logged in admin
        if ($admin->id == session('admin_user_id')) {
            return response()->json(['error' => 'Tidak dapat menghapus akun yang sedang digunakan'], 422);
        }

        $admin->delete();

        return response()->json(['success' => 'Admin berhasil dihapus']);
    }

    public function toggleStatus($id)
    {
        if (!session('admin_logged_in')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $admin = User::findOrFail($id);
        
        // Prevent deactivating the currently logged in admin
        if ($admin->id == session('admin_user_id')) {
            return response()->json(['error' => 'Tidak dapat menonaktifkan akun yang sedang digunakan'], 422);
        }

        // For simplicity, we'll use email_verified_at as status indicator
        if ($admin->email_verified_at) {
            $admin->update(['email_verified_at' => null]);
            $status = 'inactive';
        } else {
            $admin->update(['email_verified_at' => now()]);
            $status = 'active';
        }

        return response()->json(['success' => "Status admin berhasil diubah menjadi $status", 'status' => $status]);
    }
}
