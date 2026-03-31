# Cara Penggunaan Sistem Perpustakaan Digital

## 🚀 Memulai Aplikasi

1. **Start Server**
   ```bash
   php artisan serve
   ```
   Aplikasi akan berjalan di: http://127.0.0.1:8000

2. **Akses Aplikasi**
   - Landing Page: http://127.0.0.1:8000
   - Login: http://127.0.0.1:8000/login

## 👥 Akun Default

### Admin
- Email: `admin@example.com`
- Password: `password`

### Petugas
- Email: `petugas@example.com`
- Password: `password`

### User
- Email: `user@example.com`
- Password: `password`

## 📚 Fitur Berdasarkan Role

### ADMIN
1. **Dashboard** - Lihat statistik perpustakaan
2. **Kelola Buku** - Lihat semua buku (CRUD akan ditambahkan)
3. **Kelola Kategori** - Lihat semua kategori
4. **Kelola Petugas** - Lihat daftar petugas
5. **Kelola User** - Lihat dan hapus user
6. **Peminjaman** - Konfirmasi peminjaman dan pengembalian
7. **Laporan** - Cetak laporan

### PETUGAS
1. **Dashboard** - Lihat statistik peminjaman
2. **Kelola Buku** - Lihat semua buku
3. **Kelola Kategori** - Lihat kategori (opsional)
4. **Peminjaman** - Konfirmasi peminjaman dan pengembalian
5. **Laporan** - Cetak laporan

### USER
1. **Dashboard** - Lihat rekomendasi buku
2. **Katalog Buku** - Jelajahi dan pinjam buku
3. **Peminjaman Saya** - Lihat status peminjaman
4. **Riwayat** - Lihat riwayat dan tulis ulasan

## 🔄 Alur Peminjaman

### Dari Sisi User:
1. Login sebagai user
2. Buka **Katalog Buku**
3. Klik tombol **Pinjam** pada buku yang tersedia
4. Isi form peminjaman (tanggal pinjam & jatuh tempo)
5. Klik **Ajukan Peminjaman**
6. Lihat bukti peminjaman (bisa dicetak)
7. Status: **Menunggu Konfirmasi**

### Dari Sisi Admin/Petugas:
1. Login sebagai admin/petugas
2. Buka menu **Peminjaman**
3. Tab **Menunggu Konfirmasi**
4. Klik **Setujui** atau **Tolak**
5. Jika disetujui, stok buku berkurang otomatis

## 🔙 Alur Pengembalian

### Dari Sisi User:
1. Buka **Peminjaman Saya**
2. Klik tombol **Kembalikan** pada buku yang dipinjam
3. Lihat detail pengembalian (termasuk denda jika terlambat)
4. Klik **Ajukan Pengembalian**
5. Status: **Menunggu Konfirmasi Pengembalian**

### Dari Sisi Admin/Petugas:
1. Buka menu **Peminjaman**
2. Tab **Pengembalian**
3. Klik **Konfirmasi Kembali** atau **Tolak**
4. Jika dikonfirmasi, stok buku bertambah otomatis

## 📊 Data yang Tersedia

### Kategori (6):
- Fiksi
- Sejarah
- Inspiratif
- Roman
- Fiksi Ilmiah
- Sastra

### Buku (8):
1. Laskar Pelangi - Andrea Hirata
2. Bumi Manusia - Pramoedya Ananta Toer
3. Negeri 5 Menara - Ahmad Fuadi
4. Sang Pemimpi - Andrea Hirata
5. Perahu Kertas - Dee Lestari
6. Supernova - Dee Lestari
7. Ronggeng Dukuh Paruk - Ahmad Tohari
8. Cantik Itu Luka - Eka Kurniawan

## ⚙️ Reset Database

Jika ingin reset database dan data:
```bash
php artisan migrate:fresh --seed
```

## 🎯 Status Peminjaman

- **pending** - Menunggu konfirmasi admin/petugas
- **approved** - Disetujui, sedang dipinjam
- **rejected** - Ditolak oleh admin/petugas
- **return_pending** - Menunggu konfirmasi pengembalian
- **returned** - Sudah dikembalikan

## 💰 Denda Keterlambatan

- Denda: Rp 1.000/hari
- Durasi standar: 14 hari
- Maksimal perpanjangan: 1x (7 hari)

## 🖨️ Fitur Cetak

- User dapat mencetak bukti peminjaman
- Admin/Petugas dapat cetak laporan (fitur akan ditambahkan)

## ✅ Checklist Fitur yang Sudah Berfungsi

- [x] Authentication (Login/Register/Logout)
- [x] Role-based access (Admin, Petugas, User)
- [x] Dashboard untuk semua role (dengan data dari database)
- [x] Katalog buku (dari database)
- [x] Form peminjaman buku
- [x] Bukti peminjaman (bisa dicetak)
- [x] Form pengembalian buku
- [x] Konfirmasi peminjaman (Admin/Petugas)
- [x] Konfirmasi pengembalian (Admin/Petugas)
- [x] Auto update stok buku
- [x] Validasi peminjaman (tidak bisa pinjam buku yang sama)
- [x] Perhitungan denda keterlambatan
- [x] Kelola buku (view)
- [x] Kelola kategori (view)
- [x] Kelola user (view)
- [x] Kelola petugas (view)

## 🔜 Fitur yang Akan Ditambahkan

- [ ] CRUD Buku (Create, Update, Delete)
- [ ] CRUD Kategori
- [ ] CRUD User/Petugas
- [ ] Review/Ulasan buku
- [ ] Laporan (PDF)
- [ ] Perpanjangan peminjaman
- [ ] Notifikasi jatuh tempo
- [ ] Search & Filter buku

## 📝 Catatan

- Semua data sudah menggunakan database (tidak hardcoded)
- UI/UX konsisten dengan tema perpustakaan digital
- Bahasa Indonesia untuk semua teks
- Responsive design
- Print-friendly untuk bukti peminjaman
