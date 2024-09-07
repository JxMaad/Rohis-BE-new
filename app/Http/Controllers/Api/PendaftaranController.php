<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pendaftaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PendaftaranController extends Controller
{
    public function semuaPendaftar()
    {
        $pendaftarans = Pendaftaran::when(request()->search, function ($pendaftarans) {
            $pendaftarans = $pendaftarans->where('nama', 'kelas' . request()->search);
        })->latest()->paginate(10);

        //append query string to pagination links
        $pendaftarans->appends(['search' => request()->search]);

        // Mengembalikan data pengguna dalam bentuk JSON
        return response()->json([
            'status' => 'success',
            'message' => 'data pendaftar berhasil ditampilkan',
            'data' => $pendaftarans
        ], 201);
    }

    public function tambahPendaftar(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama'  => 'required',
            'kelas' => 'required',
            'motto_hidup'   => 'required',
            'alasan_masuk'  => 'required',
            'image' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
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
            $image->storeAs('public/pendaftar', $image->hashName());
            $imageName = $image->hashName();
        }

        try {
            // Create user
            $pendaftaran = Pendaftaran::create([
                'nama'     => $request->input('nama'),
                'kelas'    => $request->input('kelas'),
                'motto_hidup' => $request->input('motto_hidup'),
                'alasan_masuk'  => $request->input('alasan_masuk'),
                'image'    => $imageName,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'pendaftaran berhasil ditambahkan',
                'data' => $pendaftaran
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'pendaftaran gagal ditambahkan',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function editPendaftar(Request $request, $id)
    {
        $pendaftaran = Pendaftaran::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'nama'  => 'required',
            'kelas' => 'required',
            'motto_hidup'   => 'required',
            'alasan_masuk'  => 'required',
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
            Storage::disk('local')->delete('public/pendaftar/' . basename($pendaftaran->image));
        
            // Simpan gambar baru
            $image = $request->file('image');
            $imageName = $image->hashName(); // Ambil nama file
            $image->storeAs('public/pendaftar', $imageName); // Simpan ke folder 'public/mentoring'

            $pendaftaran->update([
                'nama'     => $request->input('nama'),
                'kelas'    => $request->input('kelas'),
                'motto_hidup' => $request->input('motto_hidup'),
                'alasan_masuk'  => $request->input('alasan_masuk'),
                'image'    => $imageName,
            ]);
        } else {
            $pendaftaran->update([
                'nama'     => $request->input('nama'),
                'kelas'    => $request->input('kelas'),
                'motto_hidup' => $request->input('motto_hidup'),
                'alasan_masuk'  => $request->input('alasan_masuk'),
            ]);
        }

        try {
            if ($pendaftaran->wasChanged())
                return response()->json([
                    'status' => 'success',
                    'message' => 'pendaftaran berhasil diedit',
                    'data' => $pendaftaran
                ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'pendaftaran gagal diedit',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function tampilPendaftar(Request $request, $id)
    {
        $pendaftaran = Pendaftaran::find($request->id);

        try {
            return response()->json([
                'status' => 'success',
                'message' => 'pendaftar berhasil ditampilkan',
                'data' => $pendaftaran
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'pendaftar gagal ditampilkan',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function hapusPendaftar($id)
    {
        $pendaftaran = Pendaftaran::find($id);

        if (!$pendaftaran) {
            // Jika kegiatan tidak ditemukan, kembalikan respons JSON
            return response()->json([
                'status' => 'error',
                'message' => 'pendaftar tidak ditemukan!'
            ], 404);
        }

        Storage::disk('local')->delete('public/pendaftar/' . basename($pendaftaran->image));

        // Hapus kegiatan dari database
        if ($pendaftaran->delete()) {
            // Kembalikan respons berhasil dalam bentuk JSON
            return response()->json([
                'status' => 'success',
                'message' => 'pendaftar berhasil dihapus!'
            ], 200);
        }

        // Jika gagal menghapus, kembalikan respons gagal dalam bentuk JSON
        return response()->json([
            'status' => 'error',
            'message' => 'pendaftar gagal dihapus!'
        ], 500);
    }
}