<?php

namespace Database\Seeders;

use App\Models\Professional;
use Illuminate\Database\Seeder;

class ProfessionalSeeder extends Seeder
{
    /**
     * Isi tabel professionals dengan 8 psikolog contoh berbahasa Indonesia.
     */
    public function run(): void
    {
        $professionals = [
            [
                'name' => 'Dr. Ratna Wulandari, M.Psi.',
                'title' => 'Psikolog Klinis',
                'specialization' => 'Kecemasan',
                'city' => 'Jakarta',
                'price_per_session' => 350000,
                'photo' => null,
                'bio' => 'Saya membantu klien memahami dan mengelola kecemasan melalui pendekatan yang hangat dan berbasis bukti. Setiap langkah kecil menuju ketenangan sangat berarti.',
                'is_verified' => true,
            ],
            [
                'name' => 'Dimas Prasetyo, M.Psi.',
                'title' => 'Psikolog Klinis',
                'specialization' => 'Depresi',
                'city' => 'Bandung',
                'price_per_session' => 300000,
                'photo' => null,
                'bio' => 'Berpengalaman mendampingi individu yang berjuang dengan depresi. Ruang konseling saya adalah tempat yang aman untuk bercerita tanpa takut dihakimi.',
                'is_verified' => true,
            ],
            [
                'name' => 'Sari Maharani, M.Psi.',
                'title' => 'Psikolog',
                'specialization' => 'Hubungan',
                'city' => 'Surabaya',
                'price_per_session' => 275000,
                'photo' => null,
                'bio' => 'Saya membantu pasangan dan individu membangun komunikasi yang sehat serta hubungan yang lebih bermakna. Mari perbaiki bersama, pelan-pelan.',
                'is_verified' => true,
            ],
            [
                'name' => 'Andi Nugroho, M.Psi.',
                'title' => 'Psikolog Klinis',
                'specialization' => 'Stres Kerja',
                'city' => 'Jakarta',
                'price_per_session' => 400000,
                'photo' => null,
                'bio' => 'Fokus pada kesehatan mental di dunia kerja: burnout, tekanan target, dan keseimbangan hidup. Kamu tidak harus kuat sendirian.',
                'is_verified' => true,
            ],
            [
                'name' => 'Maya Kusuma, M.Psi.',
                'title' => 'Psikolog Klinis',
                'specialization' => 'Trauma',
                'city' => 'Yogyakarta',
                'price_per_session' => 325000,
                'photo' => null,
                'bio' => 'Mendampingi proses pemulihan trauma dengan pendekatan yang lembut dan bertahap. Penyembuhan itu mungkin, dan kamu pantas mendapatkannya.',
                'is_verified' => true,
            ],
            [
                'name' => 'Budi Santoso, M.Psi.',
                'title' => 'Psikolog',
                'specialization' => 'Kecemasan',
                'city' => 'Denpasar',
                'price_per_session' => 250000,
                'photo' => null,
                'bio' => 'Saya percaya setiap orang punya kapasitas untuk tenang kembali. Bersama, kita cari strategi yang cocok untukmu mengelola kecemasan.',
                'is_verified' => true,
            ],
            [
                'name' => 'Dewi Anggraini, M.Psi.',
                'title' => 'Psikolog Anak & Remaja',
                'specialization' => 'Remaja',
                'city' => 'Bandung',
                'price_per_session' => 200000,
                'photo' => null,
                'bio' => 'Mendampingi remaja dan orang tua melewati masa-masa sulit dengan empati. Masa mudamu berharga, mari kita jaga bersama.',
                'is_verified' => false,
            ],
            [
                'name' => 'Rizky Firmansyah, M.Psi.',
                'title' => 'Psikolog',
                'specialization' => 'Stres Kerja',
                'city' => 'Surabaya',
                'price_per_session' => 150000,
                'photo' => null,
                'bio' => 'Membantu profesional muda mengenali tanda burnout dan membangun kebiasaan kerja yang lebih sehat. Langkah pertamamu sudah hebat.',
                'is_verified' => false,
            ],
        ];

        // Idempoten: aman dijalankan ulang tanpa membuat duplikat.
        foreach ($professionals as $professional) {
            Professional::firstOrCreate(
                ['name' => $professional['name']],
                $professional
            );
        }
    }
}
