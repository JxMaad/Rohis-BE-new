<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Dokumentasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DokumentasiController extends Controller
{
    public function semuaDokumentasi()
    {
        $dokumentasis = Dokumentasi::when(request()->search, function ($dokumentasis) {
            $dokumentasis = $dokumentasis->where('judul', 'tanggal_upload'. request()->search);
        })->latest()->paginate(10);

        //append query string to pagination links
        $dokumentasis->appends(['search' => request()->search]);

        // Mengembalikan data pengguna dalam bentuk JSON
        return response()->json([
            'status' => 'success',
            'message' => 'data dokumentasi berhasil ditampilkan',
            'data' => $dokumentasis
        ], 201);
    }

    public function tambahDokumentasi(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'judul' => 'required',
            'keterangan_dokumentasi' => 'required',
            'tanggal_upload' => 'required',
            'link_gdrive' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Create user
            $dokumentasi = Dokumentasi::create([
                'judul'     => $request->input('judul'),
                'keterangan_dokumentasi'    => $request->input('keterangan_dokumentasi'),
                'tanggal_upload' => $request->input('tanggal_upload'),
                'link_gdrive'    => $request->input('link_gdrive'),
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'dokumentasi berhasil ditambahkan',
                'data' => $dokumentasi
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'dokumentasi gagal ditambahkan',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function editDokumentasi(Request $request, $id)
    {
        $dokumentasi = Dokumentasi::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'judul' => 'required',
            'keterangan_dokumentasi' => 'required',
            'tanggal_upload' => 'required',
            'link_gdrive' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

            // Update with new dokumentasi
            $dokumentasi->update([
                'judul'     => $request->input('judul'),
                'keterangan_dokumentasi'    => $request->input('keterangan_dokumentasi'),
                'tanggal_upload' => $request->input('tanggal_upload'),
                'link_gdrive'    => $request->input('link_gdrive'),
            ]);

        // Simpan perubahan
        $dokumentasi->save();

        try {
            return response()->json([
                'status' => 'success',
                'message' => 'dokumentasi berhasil diedit',
                'data' => $dokumentasi
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'dokumentasi gagal diedit',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function tampilDokumentasi(Request $request, $id)
    {
        $dokumentasi = Dokumentasi::find($request->id);

        try {
            return response()->json([
                'status' => 'success',
                'message' => 'dokumentasi berhasil ditampilkan',
                'data' => $dokumentasi
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'dokumentasi gagal ditampilkan',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function hapusDokumentasi($id)
    {
        $dokumentasi = Dokumentasi::find($id);

        if (!$dokumentasi) {
            // Jika user tidak ditemukan, kembalikan respons JSON
            return response()->json([
                'status' => 'error',
                'message' => 'dokumentasi tidak ditemukan!'
            ], 404);
        }

        // Menghapus kegiatan
        if ($dokumentasi->delete()) {
            // Kembalikan respons berhasil dalam bentuk JSON
            return response()->json([
                'status' => 'success',
                'message' => 'dokumentasi berhasil dihapus!'
            ], 200);
        }

        // Jika gagal menghapus, kembalikan respons gagal dalam bentuk JSON
        return response()->json([
            'status' => 'error',
            'message' => 'dokumentasi gagal dihapus!'
        ], 500);
    }
}