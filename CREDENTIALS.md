# Kredensial Login

Berikut adalah kredensial untuk testing sistem:

## Admin
- **Email:** admin@example.com
- **Password:** password
- **Role:** admin
- **Dashboard:** /admin/dashboard

## Petugas
- **Email:** petugas@example.com
- **Password:** password
- **Role:** petugas
- **Dashboard:** /petugas/dashboard

## User
- **Email:** user@example.com
- **Password:** password
- **Role:** user
- **Dashboard:** /user/dashboard

---

## Cara Menjalankan

1. Jalankan migration:
   ```bash
   php artisan migrate:fresh
   ```

2. Jalankan seeder:
   ```bash
   php artisan db:seed --class=RoleUserSeeder
   ```

3. Jalankan server:
   ```bash
   php artisan serve
   ```

4. Akses aplikasi di browser:
   - Landing Page: http://localhost:8000
   - Login: http://localhost:8000/login
   - Register: http://localhost:8000/register

## Fitur

- ✅ Landing page dengan header (Home, About, Library)
- ✅ Button Sign Up di header
- ✅ Sistem login & register
- ✅ 3 Role: Admin, Petugas, User
- ✅ Dashboard berbeda untuk setiap role
- ✅ Redirect otomatis berdasarkan role setelah login
- ✅ Seeder untuk 3 user dengan role berbeda
