<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Dokumentasi;
use App\Models\Kegiatan;
use App\Models\Mentoring;
use App\Models\Pendaftaran;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function superadmin()
    {
        // Ambil data yang dibutuhkan oleh superadmin, misalnya data semua pengguna dan kegiatan
        $users = User::all();
        $kegiatans = Kegiatan::all();
        $pendaftarans = Pendaftaran::all();
        $mentorings = Mentoring::all();
        $dokumentasis = Dokumentasi::all();

        return response()->json([
            'status' => 'success',
            'message' => 'Data untuk superadmin',
            'data' => [
                'user' => $users,
                'kegiatan' => $kegiatans,
                'pendaftaran'  => $pendaftarans,
                'mentoring' => $mentorings,
                'dokumentasi'  => $dokumentasis,
            ]
        ]);
    }

    public function pembina()
    {
        // Ambil data untuk pembina, misalnya daftar mentor dan kegiatan terkait
        $users = User::all();
        $kegiatans = Kegiatan::all();
        $pendaftarans = Pendaftaran::all();
        $mentorings = Mentoring::all();
        $dokumentasis = Dokumentasi::all();

        return response()->json([
            'status' => 'success',
            'message' => 'Data untuk pembina',
            'data' => [
                'user' => $users,
                'kegiatan' => $kegiatans,
                'pendaftaran'  => $pendaftarans,
                'mentoring' => $mentorings,
                'dokumentasi'  => $dokumentasis,
            ]
        ]);
    }

    public function mentor()
    {
        // Ambil data yang dibutuhkan oleh mentor, misalnya daftar mentoring yang dia pimpin
        $users = User::all();
        $kegiatans = Kegiatan::all();
        $pendaftarans = Pendaftaran::all();
        $mentorings = Mentoring::all();
        $dokumentasis = Dokumentasi::all();

        return response()->json([
            'status' => 'success',
            'message' => 'Data untuk mentor',
            'data' => [
                'user' => $users,
                'kegiatan' => $kegiatans,
                'pendaftaran'  => $pendaftarans,
                'mentoring' => $mentorings,
                'dokumentasi'  => $dokumentasis,
            ]
        ]);
    }

    public function alumni()
    {
        // Ambil data yang relevan untuk alumni, misalnya daftar kegiatan yang bisa mereka ikuti
        $users = User::all();
        $kegiatans = Kegiatan::all();
        $pendaftarans = Pendaftaran::all();
        $mentorings = Mentoring::all();
        $dokumentasis = Dokumentasi::all();

        return response()->json([
            'status' => 'success',
            'message' => 'Data untuk alumni',
            'data' => [
                'user' => $users,
                'kegiatan' => $kegiatans,
                'pendaftaran'  => $pendaftarans,
                'mentoring' => $mentorings,
                'dokumentasi'  => $dokumentasis,
            ]
        ]);
    }

    public function bph()
    {
        // Ambil data yang relevan untuk BPH, misalnya daftar kegiatan dan mentoring yang membutuhkan persetujuan
        $users = User::all();
        $kegiatans = Kegiatan::all();
        $pendaftarans = Pendaftaran::all();
        $mentorings = Mentoring::all();
        $dokumentasis = Dokumentasi::all();

        return response()->json([
            'status' => 'success',
            'message' => 'Data untuk BPH',
            'data' => [
                'user' => $users,
                'kegiatan' => $kegiatans,
                'pendaftaran'  => $pendaftarans,
                'mentoring' => $mentorings,
                'dokumentasi'  => $dokumentasis,
            ]
        ]);
    }

    public function pengurusKegiatan()
    {
        // Ambil data yang relevan untuk pengurus kegiatan, misalnya daftar kegiatan yang sedang dikelola
        $users = User::all();
        $kegiatans = Kegiatan::all();
        $pendaftarans = Pendaftaran::all();
        $dokumentasis = Dokumentasi::all();
        $mentorings = Mentoring::all();

        return response()->json([
            'status' => 'success',
            'message' => 'Data untuk pengurus kegiatan',
            'data' => [
                'user' => $users,
                'kegiatan' => $kegiatans,
                'pendaftaran'  => $pendaftarans,
                'dokumentasi'  => $dokumentasis,
                'mentoring' => $mentorings,
            ]
        ]);
    }

    public function pengurusDokumentasi()
    {
        // Ambil data yang relevan untuk pengurus dokumentasi, misalnya daftar kegiatan yang butuh dokumentasi
        $users = User::all();
        $kegiatans = Kegiatan::all();
        $pendaftarans = Pendaftaran::all();
        $dokumentasis = Dokumentasi::all();
        $mentorings = Mentoring::all();

        return response()->json([
            'status' => 'success',
            'message' => 'Data untuk pengurus dokumentasi',
            'data' => [
                'user' => $users,
                'kegiatan' => $kegiatans,
                'pendaftaran'  => $pendaftarans,
                'dokumentasi'  => $dokumentasis,
                'mentoring' => $mentorings,
            ]
        ]);
    }

    public function pengurusRohis()
    {
        // Ambil data yang relevan untuk pengurus rohis, misalnya daftar kegiatan keagamaan
        $users = User::all();
        $kegiatans = Kegiatan::all();
        $pendaftarans = Pendaftaran::all();
        $dokumentasis = Dokumentasi::all();
        $mentorings = Mentoring::all();

        return response()->json([
            'status' => 'success',
            'message' => 'Data untuk pengurus rohis',
            'data' => [
                'user' => $users,
                'kegiatan' => $kegiatans,
                'pendaftaran'  => $pendaftarans,
                'dokumentasi'  => $dokumentasis,
                'mentoring' => $mentorings,
            ]
        ]);
    }
}
