<?php

namespace Database\Seeders;

use App\Models\Article;
use Illuminate\Database\Seeder;

class ArticleSeeder extends Seeder
{
    /**
     * Isi tabel articles dengan 5 artikel edukasi kesehatan mental.
     */
    public function run(): void
    {
        $articles = [
            [
                'title' => 'Mengenal Kecemasan: Kapan Wajar, Kapan Perlu Waspada',
                'slug' => 'mengenal-kecemasan',
                'excerpt' => 'Kecemasan adalah respons alami tubuh, tetapi bisa menjadi masalah bila mengganggu aktivitas harian. Kenali tanda-tandanya di sini.',
                'content' => <<<'ARTIKEL'
Kecemasan adalah respons alami tubuh terhadap situasi yang dianggap mengancam. Jantung berdebar, telapak tangan berkeringat, dan pikiran yang berlarian adalah hal yang normal sebelum presentasi atau ujian.

Namun, kecemasan perlu diwaspadai ketika terjadi terlalu sering, terlalu intens, atau tanpa pemicu yang jelas. Tanda-tandanya antara lain: sulit tidur karena pikiran yang terus berputar, menghindari situasi yang sebenarnya aman, dan keluhan fisik seperti sakit kepala atau nyeri otot yang berulang.

Langkah pertama yang bisa dilakukan adalah mengenali pemicunya. Coba catat kapan kecemasan muncul, apa yang sedang terjadi, dan bagaimana tubuhmu merespons. Jurnal seperti yang tersedia di RuangTenang bisa membantumu melihat pola tersebut.

Teknik sederhana seperti pernapasan dalam dan grounding (menyebut lima hal yang kamu lihat, empat yang kamu dengar, dan seterusnya) dapat membantu menenangkan sistem saraf dalam hitungan menit.

Ingat: mencari bantuan profesional bukan tanda kelemahan. Jika kecemasan sudah mengganggu pekerjaan, hubungan, atau kualitas hidupmu selama berminggu-minggu, pertimbangkan untuk berkonsultasi dengan psikolog.
ARTIKEL,
                'category' => 'Edukasi',
                'cover_image' => null,
                'published_at' => now()->subDays(6),
            ],
            [
                'title' => 'Teknik Pernapasan 4-7-8 untuk Menenangkan Diri',
                'slug' => 'teknik-pernapasan-4-7-8',
                'excerpt' => 'Latihan pernapasan sederhana yang bisa menenangkan sistem sarafmu dalam waktu kurang dari dua menit. Begini caranya.',
                'content' => <<<'ARTIKEL'
Teknik pernapasan 4-7-8 adalah metode sederhana yang dipopulerkan untuk membantu tubuh beralih dari mode "waspada" ke mode "tenang". Cara melakukannya:

1. Duduk atau berbaring dengan nyaman, letakkan ujung lidah di langit-langit mulut tepat di belakang gigi depan.
2. Hembuskan napas sepenuhnya melalui mulut dengan suara mendesis pelan.
3. Tutup mulut, lalu tarik napas perlahan melalui hidung selama 4 hitungan.
4. Tahan napas selama 7 hitungan.
5. Hembuskan napas perlahan melalui mulut selama 8 hitungan.
6. Ulangi siklus ini sebanyak 3–4 kali.

Mengapa ini bekerja? Menahan napas dan menghembuskan secara perlahan mengaktifkan sistem saraf parasimpatis, yaitu sistem yang bertugas menenangkan tubuh. Denyut jantung melambat dan ketegangan otot berkurang.

Latihan ini paling efektif bila dilakukan rutin, misalnya setiap pagi atau sebelum tidur. Kamu juga bisa menggunakannya saat panik melanda — cukup cari tempat yang tenang dan mulai siklus pertamamu.

Catatan: bila kamu memiliki gangguan pernapasan, konsultasikan dulu dengan tenaga medis sebelum berlatih teknik menahan napas.
ARTIKEL,
                'category' => 'Teknik Relaksasi',
                'cover_image' => null,
                'published_at' => now()->subDays(5),
            ],
            [
                'title' => 'Kapan Harus ke Psikolog? 7 Tanda yang Perlu Diperhatikan',
                'slug' => 'kapan-harus-ke-psikolog',
                'excerpt' => 'Tidak perlu menunggu sampai "parah" untuk berkonsultasi. Kenali tujuh tanda bahwa sudah saatnya berbicara dengan profesional.',
                'content' => <<<'ARTIKEL'
Banyak orang menunda ke psikolog karena merasa masalahnya "belum cukup parah". Padahal, bantuan profesional justru paling efektif ketika masalah masih dini. Berikut tanda-tandanya:

1. Emosimu mengganggu aktivitas harian — sulit fokus bekerja, kuliah, atau menjalani rutinitas biasa selama lebih dari dua minggu.
2. Pola tidur atau nafsu makan berubah drastis tanpa sebab fisik yang jelas.
3. Kamu menarik diri dari orang-orang terdekat dan kehilangan minat pada hal yang dulu disukai.
4. Mengandalkan alkohol, obat, atau perilaku kompulsif untuk "merasa baikan".
5. Pikiran negatif yang berulang dan sulit dikendalikan, termasuk pikiran menyakiti diri sendiri.
6. Konflik yang terus berulang dalam hubungan tanpa penyelesaian.
7. Orang terdekat mulai menyuarakan kekhawatiran tentang kondisimu.

Datang ke psikolog mirip seperti ke dokter umum saat flu — kamu tidak menunggu sampai kritis. Sesi pertama biasanya berisi perkenalan dan pemetaan masalah, tanpa tekanan apa pun.

Jika kamu merasa dalam krisis atau memiliki pikiran untuk menyakiti diri sendiri, segera hubungi layanan darurat atau profesional kesehatan mental terdekat. Kamu tidak sendirian.
ARTIKEL,
                'category' => 'Edukasi',
                'cover_image' => null,
                'published_at' => now()->subDays(4),
            ],
            [
                'title' => 'Menjaga Mood Harian: Kebiasaan Kecil, Dampak Besar',
                'slug' => 'menjaga-mood-harian',
                'excerpt' => 'Mood yang stabil dibangun dari kebiasaan kecil yang konsisten. Lima kebiasaan sederhana ini bisa kamu mulai hari ini.',
                'content' => <<<'ARTIKEL'
Mood bukanlah sesuatu yang sepenuhnya di luar kendali. Penelitian menunjukkan bahwa kebiasaan harian yang sederhana berpengaruh besar pada kestabilan emosi:

1. Tidur yang cukup dan teratur. Kurang tidur membuat otak lebih reaktif terhadap emosi negatif. Usahakan jam tidur dan bangun yang konsisten, bahkan di akhir pekan.
2. Gerak tubuh setiap hari. Tidak harus olahraga berat — jalan kaki 20 menit sudah terbukti meningkatkan mood lewat pelepasan endorfin.
3. Paparan cahaya pagi. Sinar matahari pagi membantu mengatur ritme sirkadian dan produksi serotonin.
4. Mencatat mood secara rutin. Dengan melacak mood setiap hari, kamu bisa melihat pola: apa yang menaikkan dan apa yang menurunkan semangatmu. Fitur pelacak mood di RuangTenang dibuat untuk ini.
5. Koneksi sosial, sekecil apa pun. Menyapa tetangga, menelepon teman, atau sekadar ngobrol ringan memberi rasa terhubung yang menenangkan.

Kuncinya adalah konsistensi, bukan kesempurnaan. Pilih satu kebiasaan dulu, jalani selama dua minggu, lalu tambah yang lain. Perubahan kecil yang bertahan jauh lebih kuat daripada perubahan besar yang hanya bertahan tiga hari.
ARTIKEL,
                'category' => 'Gaya Hidup',
                'cover_image' => null,
                'published_at' => now()->subDays(3),
            ],
            [
                'title' => 'Mitos Kesehatan Mental yang Masih Banyak Dipercaya',
                'slug' => 'mitos-kesehatan-mental',
                'excerpt' => '"Orang depresi itu kurang iman"? "Ke psikolog berarti gila"? Mari luruskan lima mitos yang menghambat banyak orang mencari bantuan.',
                'content' => <<<'ARTIKEL'
Stigma adalah salah satu penghalang terbesar bagi kesehatan mental di Indonesia. Mari luruskan beberapa mitos yang masih sering terdengar:

Mitos 1: "Gangguan mental berarti kurang iman atau kurang bersyukur."
Fakta: Gangguan mental adalah kondisi kesehatan dengan basis biologis, psikologis, dan sosial. Iman bisa menjadi sumber kekuatan, tetapi bukan pengganti penanganan profesional — sama seperti diabetes tidak sembuh hanya dengan doa.

Mitos 2: "Ke psikolog berarti gila."
Fakta: Sebagian besar klien psikolog adalah orang-orang biasa yang menghadapi stres, masalah hubungan, atau ingin mengenal diri lebih baik. Konseling adalah bentuk perawatan diri.

Mitos 3: "Masalah mental akan hilang sendiri seiring waktu."
Fakta: Sebagian masalah ringan memang membaik, tetapi banyak kondisi justru memburuk tanpa penanganan. Intervensi dini selalu lebih baik.

Mitos 4: "Obat psikiatri bikin ketergantungan dan mengubah kepribadian."
Fakta: Obat diresepkan dokter dengan dosis terukur dan dipantau. Tujuannya menyeimbangkan kimia otak, bukan mengubah siapa dirimu.

Mitos 5: "Orang kuat tidak butuh bantuan."
Fakta: Meminta bantuan justru butuh keberanian. Kekuatan sejati adalah mengenali batas diri dan tahu kapan harus bersandar pada orang lain.

Menyebarkan pemahaman yang benar adalah langkah kecil yang bisa kita lakukan untuk menciptakan lingkungan yang lebih suportif bagi semua orang.
ARTIKEL,
                'category' => 'Edukasi',
                'cover_image' => null,
                'published_at' => now()->subDays(2),
            ],
        ];

        // Idempoten: aman dijalankan ulang tanpa membuat duplikat.
        foreach ($articles as $article) {
            Article::firstOrCreate(
                ['slug' => $article['slug']],
                $article
            );
        }
    }
}
