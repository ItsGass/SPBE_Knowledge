# 📚 SPBE Knowledge System

SPBE Knowledge System adalah aplikasi **manajemen informasi & knowledge base** berbasis **Laravel** yang dirancang untuk mendukung pengelolaan dokumen dan informasi pada lingkungan **SPBE (Sistem Pemerintahan Berbasis Elektronik)**.

Aplikasi ini menerapkan **role-based access control**, sistem **verifikasi konten**, serta pemisahan antara **informasi publik dan internal**.

---

## 🧭 Tujuan Sistem

Sistem ini dibuat untuk:
- Menyimpan dan mengelola knowledge (dokumen, video, teks)
- Menyediakan informasi **publik** yang terverifikasi
- Mengelola informasi **internal**
- Mendukung proses **verifikasi konten**
- Menyediakan pencarian terpadu (unified search)
- Mencatat aktivitas pengguna (audit trail)

---

## 🚀 Teknologi yang Digunakan

### Backend
- **Laravel** (Framework PHP)
- **Eloquent ORM**
- **Laravel Migration & Seeder**

### Frontend
- **Blade Template Engine**
- **Tailwind CSS**
- **Vite**

### Database
- **MySQL / MariaDB**

---

## 🏗 Arsitektur Sistem

### Pola Arsitektur
- MVC (Model–View–Controller)
- Service berbasis Laravel
- Middleware untuk RBAC

### Konsep Data
- Knowledge → Scope → Tags
- Knowledge memiliki status (Draft / Verified)
- Knowledge dapat bersifat Public / Internal
- Semua aktivitas dicatat ke Activity Log

---

## 👥 Role & Hak Akses

| Role | Deskripsi | Hak Akses |
|----|----|----|
| Guest | Pengunjung | Lihat knowledge publik |
| User | Pengguna terdaftar | Lihat knowledge publik, rating, comment |
| Verifikator | Pemeriksa konten | Verifikasi knowledge, publikasi knowledge |
| Admin | Pengelola konten | CRUD knowledge, scope |
| Super Admin | Pengelola sistem | Full akses |

---

## 🧱 Struktur Database

Semua struktur database dibuat **menggunakan Laravel Migration**, bukan SQL dump, agar:
- Aman di-clone
- Konsisten antar environment
- Tidak menyebabkan error `php artisan migrate`

## 🌱 Seeder (Data Awal)

Seeder digunakan **hanya untuk data dasar**, bukan data produksi.

### Isi Seeder:
- Status default:
  - `draft`
  - `verified`
- Scope awal (opsional)
- Akun admin contoh (opsional)

Seeder **boleh dijalankan berulang** tanpa merusak data.

---

## ❓ Perbedaan Migration vs Seeder

| Migration | Seeder |
|---------|-------|
| Membuat struktur tabel | Mengisi data awal |
| Wajib untuk sistem | Opsional |
| Mengatur kolom & relasi | Mengatur isi data |
| Dijamin konsisten | Bisa diubah |

> **Migration = rangka tulang**  
> **Seeder = isi awal**

---

## 🔍 Unified Search

Sistem pencarian mendukung:
- Kata kunci bebas
- Tag
- Scope

### Format Pencarian
laporan cuaca
#desa
tag:bmkg
scope:publik
scope:3

yaml
Salin kode

Semua pencarian:
- Hanya menampilkan knowledge **public**
- Hanya yang **verified**

---

## 🖼 Thumbnail Knowledge

- Thumbnail **tidak otomatis**
- Thumbnail **diambil dari database**
- Jika tidak ada thumbnail:
  - Sistem menampilkan placeholder berdasarkan tipe (`PDF`, `VIDEO`)

✔ Konsisten antara:
- `knowledge.index`
- `welcome (public)`

---

## 🌓 Dark Mode

- Auto mengikuti sistem
- Bisa toggle manual
- Status disimpan di `localStorage`
- Tidak menyebabkan FOUC

---

## 📊 Activity Log

Setiap aksi penting dicatat:
- Create knowledge
- Update knowledge
- Verify knowledge
- Delete knowledge
- Login / Logout

Digunakan untuk:
- Audit
- Tracking aktivitas

---

## ⚙️ Cara Instalasi

🚀 Instalasi & Menjalankan Aplikasi

Ikuti langkah-langkah berikut untuk menjalankan SPBE Knowledge System di lokal.

1️⃣ Clone Repository
git clone https://github.com/ItsGass/spbe-knowledge-system.git
cd spbe-knowledge-system

2️⃣ Install Dependency
Backend (Laravel / PHP)
composer install

Frontend (Vite / Node.js)
npm install
npm run build


💡 Gunakan npm run dev hanya untuk development lokal (hot reload).

3️⃣ Setup Environment

Salin file environment:

cp .env.example
ubah menjadi .env


Generate application key:

php artisan key:generate


Lalu atur konfigurasi database di file .env:

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=(sesuai db anda)
DB_USERNAME=root
DB_PASSWORD=


Pastikan database sudah dibuat di MySQL sebelum lanjut.

4️⃣ Storage & Permission

Buat symbolic link untuk storage:

php artisan storage:link


Pastikan folder berikut ada & writable:

storage/
bootstrap/cache/

5️⃣ Jalankan Migration

Untuk membuat struktur database:

php artisan migrate


Jika ingin langsung mengisi data awal (status, role, user contoh, dll):

php artisan migrate --seed

6️⃣ Jalankan Aplikasi
php artisan serve

## Storage Setup (Required)

After cloning the repository, create the storage symlink:

bash
"php artisan storage:link"
This is required for:

Thumbnail upload

File attachment

Public assets stored in storage/app/public
Akses aplikasi melalui browser:

http://127.0.0.1:8000
🔑 Akun Default (Seeder)

Semua akun ini HANYA untuk development, bukan production.



👑 Super Admin

Email: super@admin.com

🔐 Password Default
password

Role: super_admin

Akses:

Full akses sistem

Kelola user

Kelola role

Kelola semua knowledge

Verifikasi & publikasi

🛠 Admin

Email: 

Role: admin

Akses:

CRUD knowledge

Kelola scope

Tidak bisa kelola super admin

✅ Verifikator

Email: 

Role: verifikator

Akses:

Mengubah status draft → verified

Mengubah internal → public

Tidak bisa menghapus knowledge

👤 User Biasa

Email: 

Role: user

Akses:

Melihat knowledge publik

