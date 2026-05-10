<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Content;
use App\Models\User;

class DummyContentSeeder extends Seeder
{
    public function run(): void
    {
        $staff = User::where('role', 'staff')->first();
        if (!$staff) {
            $staff = User::first(); // Fallback if no staff
        }

        $videos = [
            'Belajar Coding dari Nol: Bisa Jadi Programmer dalam 7 Hari?',
            'Apa itu Artificial Intelligence? Penjelasan Simpel Buat Pemula',
            'Eksperimen Machine Learning: Prediksi Data Sederhana',
            'Ngoding 24 Jam Nonstop, Seberapa Jauh Progresnya?',
            'Perbandingan Bahasa Pemrograman Populer: Mana yang Terbaik?',
            'Cara Bikin Website Sederhana Pakai HTML, CSS, dan JavaScript',
            'Rahasia Menjadi Full-stack Developer Handal',
            'Tips Lolos Interview Kerja Programmer',
            'Memahami Struktur Data dan Algoritma',
            'Belajar React.js untuk Pemula',
            'Pengenalan Vue.js 3 dan Composition API',
            'Membuat REST API dengan Laravel 11',
            'Cara Mengamankan Website dari Serangan Hacker',
            'Mengenal Docker dan Kubernetes',
            'Tutorial Lengkap Git dan GitHub',
            'Kenapa Harus Pindah ke Tailwind CSS?',
            'Tips Debugging Kode Biar Gak Stres',
            'Mengenal Pola Desain (Design Patterns) di PHP',
        ];

        $documents = [
            'Dasar-Dasar Pemrograman untuk Pemula: Dari Nol Sampai Bisa',
            'Mengenal Dunia Artificial Intelligence: Konsep dan Implementasi',
            'Belajar Web Development: Panduan Lengkap HTML, CSS, dan JavaScript',
            'Pengantar Data Science: Cara Mengolah Data Secara Efektif',
            'Cybersecurity 101: Cara Melindungi Data di Era Digital',
            'Panduan Membangun Startup Teknologi',
            'E-book: Menguasai Python dalam 30 Hari',
            'Modul Pembelajaran Basis Data Relasional',
            'Kumpulan Soal Logika Pemrograman',
            'Tips Trik Mempercepat Kinerja Website',
            'Strategi SEO untuk Web Developer',
            'Panduan Desain UI/UX yang Menarik',
            'Buku Pintar Laravel Eloquent',
            'Cheat Sheet Perintah Git Lengkap',
        ];

        foreach($videos as $index => $title) {
            Content::create([
                'uploader_id' => $staff->id,
                'title' => $title,
                'type' => 'video',
                'file_url' => 'dummy/video_' . $index . '.mp4',
                'thumbnail_url' => '',
                'created_at' => now()->subHours(rand(1, 300)),
            ]);
        }

        foreach($documents as $index => $title) {
            Content::create([
                'uploader_id' => $staff->id,
                'title' => $title,
                'type' => 'ebook',
                'file_url' => 'dummy/doc_' . $index . '.pdf',
                'thumbnail_url' => '',
                'created_at' => now()->subHours(rand(1, 300)),
            ]);
        }
    }
}
