<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Mentoring;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class MentoringController extends Controller
{
    public function semuaMentoring()
    {
        $mentorings = Mentoring::when(request()->search, function ($mentorings) {
            $mentorings = $mentorings->where('tanggal_mentoring', 'nama_mentor' . request()->search);
        })->latest()->paginate(10);

        //append query string to pagination links
        $mentorings->appends(['search' => request()->search]);

        // Mengembalikan data pengguna dalam bentuk JSON
        return response()->json([
            'status' => 'success',
            'message' => 'data mentoring berhasil ditampilkan',
            'data' => $mentorings
        ], 201);
    }

    public function tambahMentoring(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tanggal_mentoring' => 'required',
            'nama_mentor' => 'required',
            'tempat_mentoring' => 'required',
            'materi_singkat' => 'required',
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
            $image->storeAs('public/mentoring', $image->hashName());
            $imageName = $image->hashName();
        }

        try {
            $mentoring = Mentoring::create([
                'tanggal_mentoring' => $request->input('tanggal_mentoring'),
                'nama_mentor' => $request->input('nama_mentor'),
                'tempat_mentoring' => $request->input('tempat_mentoring'),
                'materi_singkat' => $request->input('materi_singkat'),
                'image' => $imageName,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Mentoring berhasil ditambahkan',
                'data' => $mentoring
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Mentoring gagal ditambahkan',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Edit Mentoring
    public function editMentoring(Request $request, $id)
    {
        $mentoring = Mentoring::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'tanggal_mentoring' => 'required|date',
            'nama_mentor' => 'required',
            'tempat_mentoring' => 'required',
            'materi_singkat' => 'required',
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
            Storage::disk('local')->delete('public/mentoring/' . basename($mentoring->image));
        
            // Simpan gambar baru
            $image = $request->file('image');
            $imageName = $image->hashName(); // Ambil nama file
            $image->storeAs('public/mentoring', $imageName); // Simpan ke folder 'public/mentoring'
            
            $mentoring->update([
                'tanggal_mentoring' => $request->input('tanggal_mentoring'),
                'nama_mentor' => $request->input('nama_mentor'),
                'tempat_mentoring' => $request->input('tempat_mentoring'),
                'materi_singkat' => $request->input('materi_singkat'),
                'image' => $imageName,
            ]);
        } else {
            $mentoring->update([
                'tanggal_mentoring' => $request->input('tanggal_mentoring'),
                'nama_mentor' => $request->input('nama_mentor'),
                'tempat_mentoring' => $request->input('tempat_mentoring'),
                'materi_singkat' => $request->input('materi_singkat'),
            ]);
        }

        try {
            if ($mentoring->wasChanged())
                return response()->json([
                    'status' => 'success',
                    'message' => 'Mentoring berhasil diedit',
                    'data' => $mentoring
                ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Mentoring gagal diedit',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Tampilkan Mentoring
    public function tampilMentoring($id)
    {
        $mentoring = Mentoring::find($id);

        if ($mentoring) {
            return response()->json([
                'status' => 'success',
                'message' => 'Mentoring berhasil ditampilkan',
                'data' => $mentoring
            ], 200);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Mentoring tidak ditemukan'
            ], 404);
        }
    }

    // Hapus Mentoring
    public function hapusMentoring($id)
    {
        $mentoring = Mentoring::find($id);

        if (!$mentoring) {
            return response()->json([
                'status' => 'error',
                'message' => 'Mentoring tidak ditemukan!'
            ], 404);
        }

        Storage::disk('local')->delete('public/mentoring/' . basename($mentoring->image));

        if ($mentoring->delete()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Mentoring berhasil dihapus!'
            ], 200);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Mentoring gagal dihapus!'
        ], 500);
    }
}
