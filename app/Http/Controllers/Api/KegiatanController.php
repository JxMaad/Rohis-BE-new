<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Kegiatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class KegiatanController extends Controller
{
    public function semuaKegiatan()
    {
        $kegiatans = Kegiatan::when(request()->search, function ($kegiatans) {
            $kegiatans = $kegiatans->where('judul', 'tanggal_kegiatan'. request()->search);
        })->latest()->paginate(10);

        //append query string to pagination links
        $kegiatans->appends(['search' => request()->search]);

        // Mengembalikan data pengguna dalam bentuk JSON
        return response()->json([
            'status' => 'success',
            'message' => 'data kegiatan berhasil ditampilkan',
            'data' => $kegiatans
        ], 201);
    }

    public function tambahKegiatan(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'judul' => 'required',
            'deskripsi' => 'required',
            'tanggal_kegiatan'  => 'required',
            'image' => 'required|image|mimes:jpeg,jpg,png|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        // Simpan gambar ke storage jika ada
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $image->storeAs('public/kegiatan', $image->hashName());
            $imageName = $image->hashName();
        }

        try {
            // Create user
            $kegiatan = Kegiatan::create([
                'judul'     => $request->input('judul'),
                'deskripsi'    => $request->input('deskripsi'),
                'tanggal_kegiatan' => $request->input('tanggal_kegiatan'),
                'image'    => $imageName,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'kegiatan berhasil ditambahkan',
                'data' => $kegiatan
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'kegiatan gagal ditambahkan',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function editKegiatan(Request $request, $id)
    {
        $kegiatan = Kegiatan::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'judul' => 'required',
            'deskripsi' => 'required',
            'tanggal_kegiatan'  => 'required',
            'image' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        if ($request->file('image')) {
            // Hapus gambar lama jika ada
            Storage::disk('local')->delete('public/kegiatan/' . basename($kegiatan->image));
        
            // Simpan gambar baru
            $image = $request->file('image');
            $imageName = $image->hashName(); // Ambil nama file
            $image->storeAs('public/kegiatan', $imageName); // Simpan ke folder 'public/mentoring'

            $kegiatan->update([
                'judul'     => $request->input('judul'),
                'deskripsi'    => $request->input('deskripsi'),
                'tanggal_kegiatan' => $request->input('tanggal_kegiatan'),
                'image'    => $imageName,
            ]);
        } else {
            $kegiatan->update([
                'judul'     => $request->input('judul'),
                'deskripsi'    => $request->input('deskripsi'),
                'tanggal_kegiatan' => $request->input('tanggal_kegiatan'),
            ]);
        }

        try {
            if ($kegiatan->wasChanged())
                return response()->json([
                    'status' => 'success',
                    'message' => 'kegiatan berhasil diedit',
                    'data' => $kegiatan
                ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'kegiatan gagal diedit',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function tampilKegiatan(Request $request, $id)
    {
        $kegiatan = Kegiatan::find($request->id);

        try {
            return response()->json([
                'status' => 'success',
                'message' => 'kegiatan berhasil ditampilkan',
                'data' => $kegiatan
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'kegiatan gagal ditampilkan',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function hapusKegiatan($id)
    {
        $kegiatan = Kegiatan::find($id);

        if (!$kegiatan) {
            // Jika kegiatan tidak ditemukan, kembalikan respons JSON
            return response()->json([
                'status' => 'error',
                'message' => 'kegiatan tidak ditemukan!'
            ], 404);
        }

        Storage::disk('local')->delete('public/kegiatan/' . basename($kegiatan->image));

        // Hapus kegiatan dari database
        if ($kegiatan->delete()) {
            // Kembalikan respons berhasil dalam bentuk JSON
            return response()->json([
                'status' => 'success',
                'message' => 'kegiatan berhasil dihapus!'
            ], 200);
        }

        // Jika gagal menghapus, kembalikan respons gagal dalam bentuk JSON
        return response()->json([
            'status' => 'error',
            'message' => 'kegiatan gagal dihapus!'
        ], 500);
    }
}