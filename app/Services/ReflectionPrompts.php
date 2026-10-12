<?php

namespace App\Services;

/**
 * Kumpulan prompt refleksi berbahasa Indonesia untuk jurnal terpandu.
 *
 * Prompt dirancang hangat dan tidak menghakimi, mencakup tema syukur,
 * emosi sulit, relasi, tubuh, dan harapan. Isi jurnal pengguna tidak
 * pernah dicatat (log) di mana pun — ini hanya teks panduan statis.
 */
class ReflectionPrompts
{
    /**
     * Kembalikan semua prompt refleksi sebagai array ['id', 'text'].
     *
     * @return list<array{id: string, text: string}>
     */
    public static function all(): array
    {
        return [
            [
                'id' => 'syukur-hari-ini',
                'text' => 'Apa satu hal yang membuatmu bersyukur hari ini?',
            ],
            [
                'id' => 'momen-membekas',
                'text' => 'Ceritakan momen yang paling membekas hari ini dan alasannya.',
            ],
            [
                'id' => 'beban-pikiran',
                'text' => 'Apa yang sedang membebani pikiranmu? Tuliskan tanpa disensor.',
            ],
            [
                'id' => 'rasa-tenang',
                'text' => 'Kapan terakhir kali kamu merasa benar-benar tenang? Apa yang terjadi saat itu?',
            ],
            [
                'id' => 'perasaan-pagi',
                'text' => 'Bagaimana perasaanmu pagi ini, dari skala 1 sampai 5? Apa yang memengaruhinya?',
            ],
            [
                'id' => 'diri-sendiri',
                'text' => 'Satu hal baik apa yang sudah kamu lakukan untuk dirimu sendiri akhir-akhir ini?',
            ],
            [
                'id' => 'relasi-bermakna',
                'text' => 'Siapa orang yang paling memengaruhi harimu hari ini, dan bagaimana rasanya?',
            ],
            [
                'id' => 'tubuh-bercerita',
                'text' => 'Apa yang sedang dirasakan tubuhmu saat ini? Lelah, tegang, ringan, atau yang lain?',
            ],
            [
                'id' => 'emosi-sulit',
                'text' => 'Emosi apa yang paling sulit kamu hadapi minggu ini? Coba beri nama dan ceritakan.',
            ],
            [
                'id' => 'harapan-kecil',
                'text' => 'Satu harapan kecil apa yang ingin kamu wujudkan minggu ini?',
            ],
            [
                'id' => 'belajar-dari-kesalahan',
                'text' => 'Apa satu hal yang tidak berjalan sesuai rencana hari ini, dan apa yang bisa kamu pelajari darinya?',
            ],
            [
                'id' => 'surat-untuk-diri',
                'text' => 'Tulis surat singkat untuk dirimu satu tahun dari sekarang. Apa yang ingin kamu sampaikan?',
            ],
        ];
    }
}
