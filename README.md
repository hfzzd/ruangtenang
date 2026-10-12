# RuangTenang

**Ruang aman untuk kesehatan mentalmu.** 🌿

> ### ⚠️ Disclaimer
>
> **RuangTenang bukan pengganti bantuan profesional.**
>
> Aplikasi ini adalah pendamping kesejahteraan diri: tempat menulis jurnal, memantau suasana hati, dan menemukan informasi tentang layanan kesehatan mental. Jika kamu atau orang yang kamu kenal mengalami krisis, segera hubungi bantuan profesional — di Indonesia, kamu bisa menghubungi **layanan konseling psikolog** atau datang ke fasilitas kesehatan terdekat. Menulis jurnal dan mencatat mood tidak menggantikan diagnosis atau terapi dari profesional.

## Apa itu RuangTenang?

RuangTenang adalah platform kesehatan mental berbahasa Indonesia: jurnal terpandu, pelacakan mood harian, direktori psikolog, dan artikel edukasi — semuanya dalam satu aplikasi web yang hangat dan ramah.

### 💯 Self-Hosted — Data Kamu Tetap di Mesinmu Sendiri

RuangTenang dirancang untuk **jalan di mesinmu sendiri**, bukan di server orang lain:

1. `git clone` repositori ini
2. `docker compose up -d`
3. Buka `http://localhost:8000` — selesai!

Tidak ada akun cloud yang wajib dibuat, tidak ada data jurnal yang dikirim ke pihak ketiga. **Semua tulisan jurnal, catatan mood, dan data pribadimu tersimpan di database yang hidup di mesinmu sendiri.** Itulah nilai privasi yang kami pegang sejak hari pertama.

## Fitur

### ✅ MVP (Day-1)
- **📝 Jurnal Terpandu** — tulis curahan hati dengan pertanyaan panduan yang membantu refleksi
- **😊 Mood Tracking** — catat suasana hatimu setiap hari dan lihat polanya dari waktu ke waktu

### 🚧 Segera Hadir
- **🧑‍⚕️ Direktori Psikolog** — temukan psikolog yang tepat untukmu
- **📅 Booking Sesi** — jadwalkan sesi konseling langsung dari aplikasi
- **📚 Artikel Edukasi** — bacaan kesehatan mental berbahasa Indonesia yang terkurasi

## Cara Menjalankan (Cepat)

Prasyarat: [Docker Desktop](https://www.docker.com/products/docker-desktop/) terinstal.

```bash
git clone https://github.com/hfzzd/ruangtenang.git
cd ruangtenang
cp .env.example .env
docker compose up -d --build

# Siapkan aplikasi (sekali saja, saat pertama kali)
docker compose exec app php artisan key:generate
docker compose exec app php artisan migrate
```

Lalu buka **http://localhost:8000** di browser. 🌿

> Panduan lengkap (termasuk cara manual tanpa Docker dan troubleshooting) ada di [docs/INSTALASI.md](docs/INSTALASI.md).

## Teknologi

| Komponen   | Teknologi              |
|------------|------------------------|
| Backend    | Laravel 12 (PHP 8.2)   |
| Database   | MySQL 8                |
| Frontend   | Tailwind CSS + Alpine.js |
| Build aset | Vite                   |
| Deploy     | Docker & Docker Compose |

## Lisensi

Proyek ini dirilis di bawah lisensi [MIT](LICENSE) — bebas dipakai, dimodifikasi, dan disebarluaskan.

## Kontribusi

Kontribusi sangat diterima! Beberapa caranya:

1. *Fork* repositori ini, buat branch fitur (`git checkout -b fitur/nama-fitur`), lalu ajukan *pull request*.
2. Laporkan bug atau usulkan fitur lewat tab **Issues**.
3. Perbaiki dokumentasi — sekecil apa pun sangat berarti.

> Catatan kesehatan mental untuk kontributor: jaga ritmemu sendiri. Tidak ada PR yang lebih penting dari istirahatmu. 🌿
