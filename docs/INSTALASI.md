# Panduan Instalasi RuangTenang

Panduan langkah-demi-langkah menjalankan RuangTenang di mesinmu sendiri. Bahasa Indonesia, santai tapi lengkap. 🌿

RuangTenang itu **self-hosted**: kamu mengunduh kodenya, menjalankannya di komputermu, dan semua datamu (jurnal, mood) tersimpan di mesinmu — tidak dikirim ke mana pun.

Ada dua cara menjalankan: **(A) Cara Docker** (paling mudah, disarankan) dan **(B) Cara manual** tanpa Docker.

---

## A. Cara Docker (Disarankan) 🐳

### Prasyarat

- [Docker Desktop](https://www.docker.com/products/docker-desktop/) terinstal dan berjalan
  - Windows/macOS: unduh dari situs Docker, instal, lalu buka aplikasinya
  - Linux: ikuti [panduan instalasi Docker Engine](https://docs.docker.com/engine/install/) + pastikan `docker compose` tersedia
- Git (untuk `git clone`)

Cek prasyarat di terminal:

```bash
docker --version
docker compose version
git --version
```

Ketiganya harus menampilkan nomor versi tanpa error.

### Langkah-langkah

**1. Unduh kode sumber**

```bash
git clone https://github.com/hfzzd/ruangtenang.git
cd ruangtenang
```

**2. Siapkan file konfigurasi**

```bash
cp .env.example .env
```

**3. Bangun dan jalankan container**

```bash
docker compose up -d --build
```

Perintah ini mengunduh image MySQL 8 dan membangun image aplikasi (mungkin butuh beberapa menit saat pertama kali — hanya butuh internet di tahap ini).

Tunggu sampai database siap. Cek statusnya:

```bash
docker compose ps
```

Pastikan kedua service (`ruangtenang-app` dan `ruangtenang-db`) berstatus **healthy** / **running**.

**4. Siapkan aplikasi (sekali saja, saat pertama kali)**

```bash
docker compose exec app php artisan key:generate
docker compose exec app php artisan migrate
```

- `key:generate` mengisi `APP_KEY` di `.env` (wajib — tanpa ini Laravel tidak bisa mengenkripsi sesi).
- `migrate` membuat tabel-tabel database (jurnal, mood, pengguna, dst).

**5. Buka di browser**

Buka **http://localhost:8000** 🎉

Selamat datang di RuangTenang-mu sendiri!

### Perintah Docker yang berguna

```bash
docker compose logs -f app      # lihat log aplikasi (Ctrl+C untuk keluar)
docker compose down             # hentikan semua container (data aman di volume)
docker compose down -v          # hentikan + HAPUS data database (hati-hati!)
docker compose up -d --build    # bangun ulang setelah ada perubahan kode
```

---

## B. Cara Manual (Tanpa Docker) 🛠️

Untuk yang lebih suka menjalankan semuanya langsung di sistem operasi.

### Prasyarat

| Kebutuhan | Versi minimal |
|-----------|---------------|
| PHP       | 8.2+ (dengan ekstensi: pdo_mysql, mbstring, xml, ctype, json, bcmath, gd, zip) |
| Composer  | 2.x           |
| MySQL     | 8.x           |
| Node.js   | 20.x          |

Cek:

```bash
php -v && composer -V && mysql --version && node -v
```

### Langkah-langkah

**1. Unduh kode sumber**

```bash
git clone https://github.com/hfzzd/ruangtenang.git
cd ruangtenang
```

**2. Instal dependensi PHP**

```bash
composer install
```

**3. Instal dependensi frontend & build aset**

```bash
npm install
npm run build
```

**4. Siapkan database**

Buat database dan user MySQL (sesuaikan kata sandi):

```sql
CREATE DATABASE ruangtenang CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'ruangtenang'@'localhost' IDENTIFIED BY 'rahasia';
GRANT ALL PRIVILEGES ON ruangtenang.* TO 'ruangtenang'@'localhost';
FLUSH PRIVILEGES;
```

**5. Konfigurasi `.env`**

```bash
cp .env.example .env
```

Buka `.env` lalu sesuaikan bagian database (cara manual memakai `localhost`, bukan `db`):

```ini
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ruangtenang
DB_USERNAME=ruangtenang
DB_PASSWORD=rahasia
```

**6. Generate key & migrasi**

```bash
php artisan key:generate
php artisan migrate
```

**7. Jalankan server**

```bash
php artisan serve
```

Buka **http://localhost:8000** 🎉

---

## Troubleshooting 🔧

### Port 8000 bentrok ("address already in use")

Ada aplikasi lain yang memakai port 8000. Pilih salah satu:

- Hentikan aplikasi yang memakai port tersebut, atau
- Jalankan RuangTenang di port lain. Cara Docker: ubah mapping di `docker-compose.yml` menjadi `"8080:8000"`, lalu akses http://localhost:8080. Cara manual: `php artisan serve --port=8080`.

### Database belum ready / migrate gagal dengan "Connection refused"

MySQL butuh waktu beberapa detik untuk siap saat pertama kali dijalankan. Tunggu 20–30 detik lalu ulangi perintah `migrate`. Di Docker, kamu bisa memastikan service `db` sudah *healthy* dulu:

```bash
docker compose ps
```

Service `app` sendiri baru start setelah `db` healthy (lihat `depends_on` di `docker-compose.yml`), jadi biasanya kamu tinggal menunggu.

### Error "No application encryption key has been specified" (APP_KEY kosong)

Kamu melewatkan langkah `key:generate`. Jalankan:

```bash
# Cara Docker
docker compose exec app php artisan key:generate

# Cara manual
php artisan key:generate
```

### Error permission pada `storage/` ("Permission denied")

Laravel perlu menulis ke `storage/` dan `bootstrap/cache`. Di dalam container Docker ini sudah diatur otomatis. Untuk cara manual:

```bash
chmod -R 775 storage bootstrap/cache
```

### `npm run build` gagal

- Pastikan Node.js versi 20 (`node -v`).
- Hapus `node_modules` lalu instal ulang: `rm -rf node_modules && npm install`.
- Di Docker, kegagalan build aset terjadi saat tahap *build image*; lihat lognya dengan `docker compose build` tanpa `-d`.

### Container `app` restart terus-menerus

Lihat log untuk tahu penyebabnya:

```bash
docker compose logs app
```

Penyebab umum: `.env` belum ada (lupa `cp .env.example .env`) atau `APP_KEY` belum di-generate.

---

## FAQ ❓

**Apakah data saya keluar dari mesin saya?**

Tidak. Semua data (jurnal, mood, akun) tersimpan di database MySQL yang berjalan di mesinmu sendiri (di Docker: volume `ruangtenang-mysql-data`). Tidak ada telemetri, tidak ada pelacakan, tidak ada pengiriman data ke pihak ketiga.

**Apakah saya butuh internet untuk menjalankan RuangTenang?**

Hanya saat *build*/instalasi pertama: mengunduh image Docker, `composer install`, dan `npm install` butuh internet. Setelah semuanya terinstal dan container berjalan, aplikasi bisa dipakai **sepenuhnya offline** di `http://localhost:8000`.

**Bagaimana cara update ke versi terbaru?**

```bash
git pull
docker compose up -d --build
docker compose exec app php artisan migrate
```

**Bagaimana cara backup data saya?**

Cara Docker — backup volume database:

```bash
docker compose exec db mysqldump -u root -p ruangtenang > backup-ruangtenang.sql
```

Simpan file `backup-ruangtenang.sql` di tempat aman.

**Saya menemukan bug / punya ide fitur. Ke mana melapor?**

Buka tab **Issues** di [github.com/hfzzd/ruangtenang](https://github.com/hfzzd/ruangtenang) dan ceritakan. Kontribusi kode via *pull request* juga sangat diterima — lihat bagian Kontribusi di [README](../README.md). 🌿
