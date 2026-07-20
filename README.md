# Smart Spend

Sistem realisasi anggaran universitas berbasis NFC-QR dengan fraud risk scoring, dirancang untuk mencegah penyalahgunaan keuangan secara preventif — memvalidasi setiap transaksi *sebelum* dana dicairkan, bukan sekadar audit setelah kejadian.

Project ini merupakan implementasi nyata dari penelitian *"Smart Spend: NFC-QR Fraud Risk Scoring for Preventing Financial Misconduct in University Budgets"*.

## Daftar Isi

- [Tentang Project](#tentang-project)
- [Fitur Utama](#fitur-utama)
- [Tech Stack](#tech-stack)
- [Alur Sistem](#alur-sistem)
- [Requirement](#requirement)
- [Instalasi](#instalasi)
- [Konfigurasi Environment](#konfigurasi-environment)
- [Menjalankan Project](#menjalankan-project)
- [Struktur Folder](#struktur-folder)
- [Alur Kerja Kontribusi](#alur-kerja-kontribusi)
- [Manajemen Tugas](#manajemen-tugas)
- [Kontributor](#kontributor)

## Tentang Project

Smart Spend mengintegrasikan tiga komponen menjadi satu alur kerja preventive-control:

- **Identifikasi NFC/RFID** — verifikasi identitas divisi lewat kartu
- **Autentikasi vendor via QR code** — memastikan vendor terdaftar dan sah
- **Fraud risk scoring berbasis aturan** — menilai risiko tiap transaksi sebelum difinalisasi

Sistem terdiri dari tiga fase utama: **persiapan Budget Plan (BP)**, **validasi & alokasi dana**, dan **eksekusi transaksi & monitoring**.

## Fitur Utama

- Role-based access control (Admin, Head of Division, Finance Staff, Leader/Rektor)
- Manajemen master data: Divisi, Vendor (dengan QR code terenkripsi), dan kartu RFID
- Alur pengajuan dan persetujuan Budget Plan bertingkat
- Virtual wallet per divisi dengan alokasi dana otomatis
- Validasi transaksi 5-layer (V-01 s.d V-05)
- Fraud risk scoring otomatis dengan klasifikasi Low/Medium/High
- Halaman approval manual untuk transaksi Pending Approval / High Risk
- Audit trail otomatis untuk setiap event transaksi
- Dashboard monitoring realisasi anggaran secara real-time
- Integrasi hardware ESP32 + NFC Module PN532 (I2C)

## Tech Stack

| Komponen | Teknologi |
|---|---|
| Backend & Frontend | Laravel (PHP) |
| Styling | Tailwind CSS |
| Database | MySQL |
| Hardware | ESP32 + NFC Module PN532 (I2C) |
| Hardware IDE | PlatformIO (VSCode extension) |
| Version Control | Git & GitHub |

## Alur Sistem

```
Kartu NFC → tap ke PN532 → ESP32 baca UID
    → kirim via WiFi (HTTP POST /api/card-check) → Backend Laravel
    → 5-layer validation + fraud risk scoring
    → simpan ke database
    → tampil di Dashboard
```

Transaksi yang mendapat skor **High Risk** atau gagal validasi tertentu (V-03/V-05) masuk ke status **Pending Approval**, dan harus direview serta di-approve/reject secara manual oleh Finance Staff atau Internal Auditor melalui halaman approval.

### Klasifikasi Risiko

| Skor | Kategori | Respons Sistem |
|---|---|---|
| 0–30 | Low | Transaksi diloloskan, tercatat di audit trail |
| 31–60 | Medium | Di-flag untuk review Finance Staff |
| 61–100 | High | Diblokir, dieskalasi, atau masuk Pending Approval |

## Requirement

Pastikan sudah terpasang di komputer Anda:

- PHP >= 8.2
- Composer
- Node.js & NPM
- MySQL
- Git

Untuk pengembangan hardware (opsional):

- VSCode dengan ekstensi PlatformIO IDE
- Driver USB-to-Serial (CP210x atau CH340 tergantung board)
- Hardware: ESP32 DevKit + NFC Module PN532

## Instalasi

```bash
# Clone repository
git clone https://github.com/nidioganteng/smart-spend.git
cd smart-spend

# Install dependency PHP
composer install

# Install dependency JavaScript & Tailwind
npm install
```

## Konfigurasi Environment

1. Salin file environment:

   ```bash
   cp .env.example .env
   ```

2. Generate application key:

   ```bash
   php artisan key:generate
   ```

3. Buat database kosong (misal `smart_spend`) lewat DBeaver/TablePlus/MySQL client lainnya.

4. Sesuaikan koneksi database di `.env`:

   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=smart_spend
   DB_USERNAME=root
   DB_PASSWORD=isi_password_anda
   ```

5. Jalankan migration (dan seeder untuk data dummy):

   ```bash
   php artisan migrate --seed
   ```

## Menjalankan Project

Buka **dua terminal terpisah** (keduanya harus tetap berjalan selama development):

```bash
# Terminal 1 — compile asset Tailwind & JS
npm run dev
```

```bash
# Terminal 2 — jalankan server Laravel
php artisan serve
```

Buka `http://127.0.0.1:8000` di browser.

## Struktur Folder

```
smart-spend/
├── app/                  # Model, Controller, Middleware, Logic bisnis
├── bootstrap/
├── config/
├── database/
│   ├── migrations/       # Struktur tabel
│   └── seeders/          # Data dummy untuk testing
├── hardware/             # Project PlatformIO untuk ESP32
│   ├── src/
│   │   └── main.cpp      # Firmware ESP32 (WiFi + NFC + HTTP POST)
│   └── platformio.ini    # Konfigurasi board & library
├── public/
├── resources/
│   ├── css/              # Entry point Tailwind
│   ├── js/
│   └── views/            # Blade templates
├── routes/
│   ├── web.php
│   └── api.php           # Endpoint untuk ESP32 (/api/card-check, /api/tap)
├── storage/
└── .env.example
```

## Alur Kerja Kontribusi

Repo ini menggunakan branch `main` sebagai branch utama yang harus selalu stabil. Setiap kontributor wajib bekerja di branch terpisah dan mengajukan Pull Request.

```bash
# 1. Pastikan main terbaru
git checkout main
git pull origin main

# 2. Buat branch baru untuk fitur yang dikerjakan
git checkout -b feature/nama-fitur

# 3. Kerjakan perubahan, lalu commit
git add .
git commit -m "Deskripsi singkat perubahan"

# 4. Push branch ke GitHub
git push -u origin feature/nama-fitur
```

Setelah push, buka GitHub → buat **Pull Request** dari branch fitur ke `main` → minta review dari anggota tim lain → merge setelah disetujui.

**Aturan penting:**
- Jangan push langsung ke `main`
- Gunakan format nama branch `feature/nama-fitur` (contoh: `feature/auth-login`, `feature/budget-plan`)
- Tulis pesan commit yang jelas dan deskriptif
- Hapus branch setelah berhasil di-merge

## Manajemen Tugas

Seluruh tugas dikelola melalui **GitHub Issues**, dikelompokkan ke dalam 7 milestone:

| Milestone | Cakupan |
|---|---|
| M1 - Fondasi & Autentikasi | Setup Laravel, login, role-based access control |
| M2 - Master Data | CRUD Divisi, Vendor, binding kartu RFID |
| M3 - Budget Plan & Virtual Wallet | Alur submit, review, approval BP, alokasi dana |
| M4 - Transaksi & Validasi | Transaksi NFC-QR, 5-layer validation, fraud scoring, approval flow |
| M5 - Audit Trail & Dashboard | Pencatatan audit trail, dashboard monitoring |
| M6 - Integrasi Hardware ESP32 | Kode Arduino ESP32, koneksi ke API Laravel |
| M7 - Testing & Finalisasi | Testing skenario, dokumentasi, polishing UI |

Setiap issue memiliki label sesuai jenis pekerjaan (`backend`, `frontend`, `database`, `hardware`, `testing`, `docs`) untuk memudahkan kontributor memilih tugas sesuai keahlian masing-masing.

Sebelum mengerjakan sebuah issue, tinggalkan komentar di issue tersebut agar tidak terjadi pekerjaan ganda antar anggota tim.

---

*Project ini dikembangkan berdasarkan penelitian akademik Smart Spend, sebagai implementasi nyata dari prototype sistem fraud risk scoring untuk realisasi anggaran universitas.*