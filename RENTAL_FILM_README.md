# Sistem Rental Film

Sistem manajemen rental film berbasis web dengan Laravel 11 dan Tailwind CSS berdasarkan PlantUML use case diagram.

## Fitur Utama

### 1. User (Pelanggan)
- ✅ Register & Login
- ✅ Browse & Search Film (filter genre, tahun, rating, harga)
- ✅ Lihat Detail Film (sinopsis, rating, reviews)
- ✅ Keranjang Sewa (tambah, update durasi, hapus)
- ✅ Checkout dengan Promo
- ✅ Pembayaran (Online/Manual dengan upload bukti)
- ✅ Lihat Sewa Saya (history & status)
- ✅ Perpanjang Sewa
- ✅ Pengembalian Film
- ✅ Beri Rating & Review
- ✅ Notifikasi (jatuh tempo, pembayaran, dll)
- ✅ Sistem Denda Keterlambatan

### 2. Pegawai (Staff)
- ✅ Dashboard dengan statistik
- ✅ Verifikasi Pembayaran Manual
- ✅ Proses Pengembalian Film
- ✅ Perpanjang Sewa (atas permintaan user)
- ✅ Kelola Keterlambatan & Denda
- ✅ Kelola Katalog Film (CRUD)
- ✅ Laporan Transaksi & Pendapatan
- ✅ Ekspor Laporan (PDF & CSV)

### 3. Owner (Pemilik)
- ✅ Dashboard dengan analytics lengkap
- ✅ Kelola Promo (kode diskon)
- ✅ Kelola User & Role
- ✅ Laporan Transaksi & Pendapatan
- ✅ Audit Log (tracking semua aktivitas admin)
- ✅ Semua fitur Pegawai

## Teknologi

- **Framework**: Laravel 11
- **Database**: MySQL, PostgreSQL
- **Frontend**: Blade, Tailwind CSS, Alpine.js
- **Authentication**: Custom (Role-based)
- **PDF Export**: barryvdh/laravel-dompdf

## Instalasi

### 1. Install Dependencies
```cmd
composer install
npm install
```

### 2. Setup Environment
```cmd
copy .env.example .env
php artisan key:generate
```

### 3. Konfigurasi Database
Edit file `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=rental_film
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Install PDF Package
```cmd
composer require barryvdh/laravel-dompdf
```

### 5. Run Migration & Seeder
```cmd
php artisan migrate
php artisan db:seed
```

### 6. Create Storage Link
```cmd
php artisan storage:link
```

### 7. Build Assets
```cmd
npm run build
```
atau untuk development:
```cmd
npm run dev
```

### 8. Run Server
```cmd
php artisan serve
```

Buka browser: `http://localhost:8000`

## Default Login Credentials

### Owner
- Email: `owner@rentalfilm.com`
- Password: `password`

### Pegawai/Staff
- Email: `staff@rentalfilm.com` atau `staff2@rentalfilm.com`
- Password: `password`

### User
- Email: `john@example.com`, `jane@example.com`, `bob@example.com`
- Password: `password`

## Database Structure

### Tables
1. **users** - Data pengguna dengan role (user, pegawai, owner)
2. **genres** - Genre film (15 genres)
3. **films** - Data film lengkap dengan rating
4. **promos** - Kode promo/diskon (percentage & fixed)
5. **carts** - Keranjang sewa user
6. **rentals** - Data transaksi sewa dengan status
7. **payments** - Data pembayaran (online & manual)
8. **reviews** - Rating & ulasan film
9. **notifications** - Notifikasi untuk user
10. **audit_logs** - Log aktivitas admin (pegawai & owner)

### Status Rental
- **pending**: Menunggu pembayaran
- **active**: Sedang disewa
- **extended**: Diperpanjang
- **returned**: Sudah dikembalikan
- **overdue**: Terlambat

### Payment Status
- **pending**: Menunggu verifikasi (manual payment)
- **verified**: Sudah diverifikasi
- **rejected**: Ditolak

## Data Seeder

### Users (6 users)
- 1 Owner: `owner@rentalfilm.com`
- 2 Pegawai: `staff@rentalfilm.com`, `staff2@rentalfilm.com`
- 3 User: `john@example.com`, `jane@example.com`, `bob@example.com`

### Films (15 films)
Film terkenal dengan data real:
- The Shawshank Redemption, The Godfather, The Dark Knight
- Pulp Fiction, Forrest Gump, Inception, The Matrix
- Goodfellas, Interstellar, The Lion King
- Parasite, Avengers: Endgame, Spirited Away
- Joker, The Lord of the Rings: The Return of the King

### Promos (5 promos)
- WELCOME10: Diskon 10% (percentage)
- WEEKEND25: Diskon 25% (percentage)
- FILM20K: Potongan Rp 20.000 (fixed)
- BIGDEAL50: Potongan Rp 50.000 (fixed)
- EXPIRED: Promo kadaluarsa (untuk testing)

## Workflow Sistem

### Rental Flow (User)
1. User browse & search film
2. Tambah film ke keranjang (pilih durasi sewa)
3. Checkout → Terapkan promo (optional)
4. Pilih metode pembayaran:
   - **Online**: Otomatis verified, rental langsung active
   - **Manual**: Upload bukti transfer, menunggu verifikasi pegawai
5. Setelah verified: Rental status = active, stock berkurang
6. User bisa perpanjang sewa (extend) atau kembalikan film (return)
7. Setelah return: User bisa beri rating & review

### Payment Verification Flow (Pegawai)
1. Pegawai lihat daftar pembayaran pending
2. Cek bukti transfer yang diupload user
3. Verify atau Reject pembayaran
4. Jika verified: Rental jadi active, stock berkurang, user dapat notifikasi
5. Jika rejected: User dapat notifikasi dengan alasan penolakan

### Overdue & Fine Flow
1. Sistem cek rental yang melewati due_date
2. Update status → overdue
3. Hitung denda: 10% dari harga rental per hari keterlambatan
4. User harus bayar denda sebelum bisa return film
5. Notifikasi otomatis terkirim ke user

### Catalog Management (Pegawai & Owner)
1. Pegawai/Owner bisa tambah, edit, hapus film
2. Upload poster film ke storage
3. Set harga rental, stock, ketersediaan
4. Semua perubahan tercatat di audit log

### Promo Management (Owner)
1. Owner buat promo (percentage atau fixed amount)
2. Set periode berlaku, minimal transaksi, usage limit
3. User bisa apply promo saat checkout
4. Sistem validasi promo secara otomatis
5. Track usage count promo

### User & Role Management (Owner)
1. Owner bisa tambah user baru dengan role apapun
2. Edit data user & change role
3. Delete user (jika tidak ada rental aktif)
4. Semua perubahan tercatat di audit log

### Audit Log (Owner)
1. Track semua aktivitas admin (create, update, delete, verify, reject, dll)
2. Simpan old values & new values
3. Simpan IP address & user agent
4. Filter by action, model, user, date range
5. View detail perubahan

## Routes

### Public Routes
- `GET /` - Home page
- `GET /films` - Browse films
- `GET /films/{slug}` - Detail film

### Auth Routes
- `GET|POST /register` - Register
- `GET|POST /login` - Login
- `POST /logout` - Logout

### User Routes (Role: user)
- `GET /cart` - Keranjang
- `GET /checkout` - Checkout
- `GET /my-rentals` - Sewa saya
- `POST /rentals/{id}/extend` - Perpanjang
- `POST /rentals/{id}/return` - Kembalikan
- `GET|POST /rentals/{id}/review` - Review

### Pegawai Routes (Role: pegawai, owner)
- `GET /pegawai/dashboard` - Dashboard pegawai
- `GET /pegawai/payments` - Verifikasi pembayaran
- `GET /pegawai/rentals` - Kelola rental
- `GET /pegawai/catalog` - Kelola katalog
- `GET /pegawai/reports` - Laporan & export

### Owner Routes (Role: owner)
- `GET /owner/dashboard` - Dashboard owner
- `GET /owner/promos` - Kelola promo
- `GET /owner/users` - Kelola users
- `GET /owner/audit-logs` - Audit log

## File Structure (Backend)

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── AuthController.php
│   │   ├── HomeController.php
│   │   ├── FilmController.php
│   │   ├── CartController.php
│   │   ├── RentalController.php
│   │   ├── ReviewController.php
│   │   ├── Pegawai/
│   │   │   ├── DashboardController.php
│   │   │   ├── PaymentVerificationController.php
│   │   │   ├── RentalManagementController.php
│   │   │   ├── CatalogController.php
│   │   │   └── ReportController.php
│   │   └── Owner/
│   │       ├── DashboardController.php
│   │       ├── PromoController.php
│   │       ├── UserRoleController.php
│   │       └── AuditLogController.php
│   └── Middleware/
│       └── RoleMiddleware.php
├── Models/
│   ├── User.php (with role methods)
│   ├── Film.php (with rating calculation)
│   ├── Genre.php
│   ├── Cart.php (with subtotal calculation)
│   ├── Rental.php (with overdue logic)
│   ├── Payment.php (with code generation)
│   ├── Promo.php (with validation & discount calculation)
│   ├── Review.php
│   ├── Notification.php
│   └── AuditLog.php (with logging method)
```

## Models & Methods

### User Model
- `isUser()`, `isPegawai()`, `isOwner()`, `isAdmin()`
- Relationships: rentals, payments, reviews, carts, notifications

### Film Model
- `updateRating()` - Update average rating dari reviews
- `isAvailable()` - Check stock & availability
- Accessor: average_rating, total_reviews

### Rental Model
- `generateRentalCode()` - Generate unique rental code
- `isOverdue()` - Check if rental is overdue
- `calculateOverdueDays()` - Calculate days overdue
- `calculateOverdueFine()` - Calculate fine amount (10% per day)
- `updateOverdueStatus()` - Update status & fine

### Promo Model
- `isValid()` - Validate promo (active, date range, usage limit)
- `calculateDiscount()` - Calculate discount amount

### Payment Model
- `generatePaymentCode()` - Generate unique payment code

### AuditLog Model
- `log()` - Static method to log activities

## Views Structure (yang perlu dibuat)

```
resources/views/
├── layouts/
│   ├── app.blade.php (sudah dibuat)
│   ├── pegawai.blade.php
│   └── owner.blade.php
├── home.blade.php (sudah dibuat)
├── auth/
│   ├── login.blade.php
│   └── register.blade.php
├── films/
│   ├── index.blade.php (browse)
│   └── show.blade.php (detail)
├── cart/
│   └── index.blade.php
├── rentals/
│   ├── checkout.blade.php
│   └── my-rentals.blade.php
├── reviews/
│   └── create.blade.php
├── pegawai/
│   ├── dashboard.blade.php
│   ├── payments/
│   │   ├── index.blade.php
│   │   └── show.blade.php
│   ├── rentals/
│   │   ├── index.blade.php
│   │   └── show.blade.php
│   ├── catalog/
│   │   ├── index.blade.php
│   │   ├── create.blade.php
│   │   └── edit.blade.php
│   └── reports/
│       ├── index.blade.php
│       ├── transactions.blade.php
│       └── pdf.blade.php
└── owner/
    ├── dashboard.blade.php
    ├── promos/
    │   ├── index.blade.php
    │   ├── create.blade.php
    │   └── edit.blade.php
    ├── users/
    │   ├── index.blade.php
    │   ├── create.blade.php
    │   └── edit.blade.php
    └── audit-logs/
        ├── index.blade.php
        └── show.blade.php
```

## Cara Kerja Sistem Denda

Contoh perhitungan:
- Harga rental: Rp 15.000/hari
- Durasi sewa: 3 hari
- Due date: 5 Desember 2024
- Return date: 8 Desember 2024 (terlambat 3 hari)

Perhitungan denda:
```php
$finePerDay = $rental->rental_price * 0.10; // 15.000 * 10% = 1.500
$overdueFine = $finePerDay * $overdueDays; // 1.500 * 3 = 4.500
```

Total yang harus dibayar untuk return = Rp 4.500

## Security Features

1. **Role-based Access Control**: Middleware untuk restrict access berdasarkan role
2. **CSRF Protection**: Token di semua form
3. **Password Hashing**: Bcrypt untuk password
4. **SQL Injection Prevention**: Eloquent ORM
5. **XSS Protection**: Blade automatic escaping
6. **Audit Logging**: Track semua aktivitas admin

## Troubleshooting

### Migration Error
```cmd
php artisan migrate:fresh --seed
```

### Storage Permission
```cmd
php artisan storage:link
```

### Clear Cache
```cmd
php artisan optimize:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### Regenerate Autoload
```cmd
composer dump-autoload
```

## Testing

### Manual Testing Steps
1. Register sebagai user baru
2. Login dan browse films
3. Tambah film ke cart dengan durasi sewa
4. Checkout dengan promo code
5. Upload bukti pembayaran
6. Login sebagai pegawai → Verify payment
7. Cek rental status jadi active
8. Test perpanjangan sewa
9. Test pengembalian
10. Beri review & rating
11. Login sebagai owner → Cek dashboard, audit log

### Test Promo Codes
- `WELCOME10` - Diskon 10%, min Rp 50.000
- `WEEKEND25` - Diskon 25%, min Rp 100.000
- `FILM20K` - Potongan Rp 20.000, min Rp 150.000
- `BIGDEAL50` - Potongan Rp 50.000, min Rp 300.000

## Next Steps

Untuk melengkapi sistem, buat views untuk:
1. Auth (login, register)
2. Films (index, show) 
3. Cart & Checkout
4. My Rentals & Reviews
5. Pegawai Dashboard & Features
6. Owner Dashboard & Features

Gunakan Tailwind CSS dan Alpine.js untuk styling & interactivity.
Layout sudah dibuat di `resources/views/layouts/app.blade.php`.

## License

MIT License
