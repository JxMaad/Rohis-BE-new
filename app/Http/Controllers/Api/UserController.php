<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function semuaUser()
    {
        $users = User::when(request()->search, function ($users) {
            $users = $users->where('nama', 'kelas', 'jurusan', 'divisi' . request()->search);
        })->whereDoesntHave('roles', function ($query) {
            $query->where('name', 'superadmin');
        })->with('roles')->latest()->paginate(10);

        //append query string to pagination links
        $users->appends(['search' => request()->search]);

        // Mengembalikan data pengguna dalam bentuk JSON
        return response()->json([
            'status' => 'success',
            'message' => 'data user berhasil ditampilkan',
            'data' => $users
        ], 201);
    }

    public function tambahUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama'         => 'required',
            'kelas'        => 'required',
            'jurusan'      => 'required',
            'divisi'       => 'required',
            'email'        => 'required|unique:users',
            'password'     => 'required|confirmed',
            'image'        => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        // Default dummy image name
        $defaultImage = 'Dummy.png';

        // Simpan gambar ke storage jika ada
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $image->storeAs('public/users', $image->hashName());
            $imageName = $image->hashName();
        } else {
            // Use default dummy image if no image uploaded
            $imageName = $defaultImage;
        }

        try {
            // Create user
            $user = User::create([
                'nama'     => $request->input('nama'),
                'kelas'    => $request->input('kelas'),
                'jurusan'  => $request->input('jurusan'),
                'divisi'   => $request->input('divisi'),
                'email'    => $request->input('email'),
                'password' => bcrypt($request->input('password')),
                'image'    => $imageName,
            ]);

            // Assign role to user
            $user->assignRole($request->roles);

            return response()->json([
                'status' => 'success',
                'message' => 'user berhasil ditambahkan',
                'data' => $user
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'user gagal ditambahkan',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function editUser(Request $request)
    {

        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'nama'         => 'required',
            'kelas'        => 'required',
            'jurusan'      => 'required',
            'divisi'       => 'required',
            'email'        => 'required|unique:users',
            'password'     => 'required|confirmed',
            'image'        => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        // Mengupdate data user lainnya
        if ($request->filled('nama')) {
            $user->nama = $request->input('nama');
        }

        if ($request->filled('kelas')) {
            $user->kelas = $request->input('kelas');
        }

        if ($request->filled('jurusan')) {
            $user->jurusan = $request->input('jurusan');
        }

        if ($request->filled('divisi')) {
            $user->divisi = $request->input('divisi');
        }

        if ($request->filled('email')) {
            $user->email = $request->input('email');
        }

        if ($request->filled('password')) {
            $user->password = bcrypt($request->input('password'));
        }

        // Default dummy image name
        $defaultImage = 'Dummy.png';

        // Mengupdate gambar jika ada gambar baru diunggah
        if ($request->hasFile('image')) {
            // Hapus gambar lama jika bukan gambar dummy
            if ($user->image !== $defaultImage && Storage::exists('public/users/' . $user->image)) {
                Storage::delete('public/users/' . $user->image);
            }

            // Simpan gambar baru
            $image = $request->file('image');
            $image->storeAs('public/users', $image->hashName());
            $user->image = $image->hashName();
        }

        // // Menyinkronkan peran pengguna jika dimasukkan
        // if ($request->filled('roles')) {
        //     $user->syncRoles($request->roles);
        // }

        // Simpan perubahan
        $user->save();

        try {
            return response()->json([
                'status' => 'success',
                'message' => 'user berhasil diedit',
                'data' => $user
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'user gagal diedit',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function tampilUser(Request $request, $id)
    {
        //get user
        $user = User::with('roles')->find($request->id);

        try {
            return response()->json([
                'status' => 'success',
                'message' => 'user berhasil ditampilkan',
                'data' => $user
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'user gagal ditampilkan',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function hapusUser($id)
    {
        // Temukan user berdasarkan ID
        $user = User::find($id);

        if (!$user) {
            // Jika user tidak ditemukan, kembalikan respons JSON
            return response()->json([
                'status' => 'error',
                'message' => 'Data User Tidak Ditemukan!'
            ], 404);
        }

        // Default dummy image name
        $defaultImage = 'Dummy.png';

        // Menghapus gambar profil jika bukan gambar dummy
        if ($user->image !== $defaultImage && Storage::exists('public/users/' . $user->image)) {
            Storage::delete('public/users/' . $user->image);
        }

        // Menghapus user
        if ($user->delete()) {
            // Kembalikan respons berhasil dalam bentuk JSON
            return response()->json([
                'status' => 'success',
                'message' => 'Data User Berhasil Dihapus!'
            ], 200);
        }

        // Jika gagal menghapus, kembalikan respons gagal dalam bentuk JSON
        return response()->json([
            'status' => 'error',
            'message' => 'Data User Gagal Dihapus!'
        ], 500);
    }
}
