# SISTEM RENTAL FILM - PANDUAN LENGKAP

## Struktur Database yang Sudah Dibuat

### 1. Migrations (10 files)
✅ `2024_12_01_000003_add_role_to_users_table.php`
✅ `2024_12_01_000004_create_genres_table.php`
✅ `2024_12_01_000005_create_films_table.php`
✅ `2024_12_01_000006_create_promos_table.php`
✅ `2024_12_01_000007_create_carts_table.php`
✅ `2024_12_01_000008_create_rentals_table.php`
✅ `2024_12_01_000009_create_payments_table.php`
✅ `2024_12_01_000010_create_reviews_table.php`
✅ `2024_12_01_000011_create_notifications_table.php`
✅ `2024_12_01_000012_create_audit_logs_table.php`

### 2. Models (10 files)
✅ User.php (dengan role methods)
✅ Genre.php
✅ Film.php (dengan rating calculation)
✅ Promo.php (dengan validation & discount calculation)
✅ Cart.php (dengan subtotal calculation)
✅ Rental.php (dengan overdue logic)
✅ Payment.php (dengan code generation)
✅ Review.php
✅ Notification.php
✅ AuditLog.php (dengan logging method)

### 3. Seeders (4 files)
✅ UserSeeder.php (6 users: 1 owner, 2 pegawai, 3 users)
✅ GenreSeeder.php (15 genres)
✅ FilmSeeder.php (15 film terkenal dengan data real)
✅ PromoSeeder.php (5 promos)
✅ DatabaseSeeder.php (orchestrator)

### 4. Controllers (14 files)

#### User Controllers
✅ AuthController.php (register, login, logout)
✅ HomeController.php (landing page)
✅ FilmController.php (browse, search, detail)
✅ CartController.php (CRUD cart)
✅ RentalController.php (checkout, my rentals, extend, return, promo)
✅ ReviewController.php (create, store review)

#### Pegawai Controllers
✅ Pegawai/DashboardController.php
✅ Pegawai/PaymentVerificationController.php (verify, reject)
✅ Pegawai/RentalManagementController.php (process return, extend, overdue)
✅ Pegawai/CatalogController.php (CRUD films)
✅ Pegawai/ReportController.php (reports, export PDF/CSV)

#### Owner Controllers
✅ Owner/DashboardController.php (analytics)
✅ Owner/PromoController.php (CRUD promos)
✅ Owner/UserRoleController.php (CRUD users, change role)
✅ Owner/AuditLogController.php (view logs)

### 5. Middleware
✅ RoleMiddleware.php (role-based access control)

### 6. Routes
✅ routes/web.php (semua routes dengan middleware)

### 7. Views (3 files sudah dibuat, 30+ files perlu dibuat)
✅ layouts/app.blade.php (main layout dengan navbar)
✅ home.blade.php (landing page)
✅ auth/login.blade.php
✅ auth/register.blade.php

## Files yang Perlu Dibuat (Views)

### User Views (8 files)
```
resources/views/films/
  ├── index.blade.php        # Browse & search films dengan filter
  └── show.blade.php         # Detail film dengan reviews

resources/views/cart/
  └── index.blade.php        # Keranjang sewa

resources/views/rentals/
  ├── checkout.blade.php     # Checkout dengan promo
  └── my-rentals.blade.php   # List semua rental user

resources/views/reviews/
  └── create.blade.php       # Form rating & review
```

### Pegawai Views (11 files)
```
resources/views/pegawai/
  ├── dashboard.blade.php    # Dashboard pegawai
  ├── payments/
  │   ├── index.blade.php    # List pending payments
  │   └── show.blade.php     # Detail payment untuk verify
  ├── rentals/
  │   ├── index.blade.php    # List all rentals
  │   └── show.blade.php     # Detail rental
  ├── catalog/
  │   ├── index.blade.php    # List films
  │   ├── create.blade.php   # Add new film
  │   └── edit.blade.php     # Edit film
  └── reports/
      ├── index.blade.php    # Dashboard laporan
      ├── transactions.blade.php  # List transactions
      └── pdf.blade.php      # Template PDF export
```

### Owner Views (10 files)
```
resources/views/owner/
  ├── dashboard.blade.php    # Dashboard owner dengan charts
  ├── promos/
  │   ├── index.blade.php    # List promos
  │   ├── create.blade.php   # Add promo
  │   └── edit.blade.php     # Edit promo
  ├── users/
  │   ├── index.blade.php    # List users
  │   ├── create.blade.php   # Add user
  │   └── edit.blade.php     # Edit user
  └── audit-logs/
      ├── index.blade.php    # List audit logs
      └── show.blade.php     # Detail log
```

## Template View yang Perlu Diikuti

Semua view menggunakan:
- Layout: `@extends('layouts.app')`
- Tailwind CSS untuk styling
- Alpine.js untuk interactivity
- Form dengan CSRF token
- Error handling & flash messages

### Contoh Structure View

```blade
@extends('layouts.app')

@section('title', 'Page Title')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="bg-white rounded-lg shadow-md p-6">
        <h1 class="text-2xl font-bold mb-6">Title</h1>
        
        <!-- Content here -->
        
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Custom JavaScript
</script>
@endpush
```

## Cara Melengkapi Sistem

### Step 1: Buat Views User
1. **films/index.blade.php**
   - Grid film cards
   - Search bar & filters (genre, year, sort)
   - Pagination
   - Link ke detail film

2. **films/show.blade.php**
   - Poster, title, genre, year, duration
   - Synopsis, director, cast
   - Rating & reviews list
   - Form tambah ke cart (pilih durasi)

3. **cart/index.blade.php**
   - List cart items dengan film info
   - Update rental days
   - Remove from cart
   - Subtotal calculation
   - Button checkout

4. **rentals/checkout.blade.php**
   - List cart items
   - Form promo code (AJAX check)
   - Subtotal, discount, total
   - Pilih payment method (online/manual)
   - Upload bukti transfer (jika manual)

5. **rentals/my-rentals.blade.php**
   - List semua rental dengan status badges
   - Show rental code, film, dates, status
   - Actions: extend, return, review (conditional)
   - Filter by status

6. **reviews/create.blade.php**
   - Rating stars (1-5)
   - Comment textarea
   - Submit button

### Step 2: Buat Views Pegawai
1. **pegawai/dashboard.blade.php**
   - Statistics cards (total, active, overdue, pending)
   - Recent rentals table
   - Pending payments table
   - Quick actions

2. **pegawai/payments/index.blade.php**
   - List pending payments
   - Show user, rental code, amount
   - View bukti transfer
   - Actions: verify, reject

3. **pegawai/payments/show.blade.php**
   - Detail payment
   - Large bukti transfer image
   - Form verify (dengan notes)
   - Form reject (dengan notes)

4. **pegawai/rentals/index.blade.php**
   - List all rentals
   - Search & filter by status
   - Show user, film, dates, status
   - Actions: view, return, extend, overdue

5. **pegawai/rentals/show.blade.php**
   - Detail lengkap rental
   - Payment history
   - Reviews (jika ada)
   - Action buttons

6. **pegawai/catalog/index.blade.php**
   - List films dengan search
   - Show title, genre, stock, price
   - Actions: edit, delete
   - Button add new

7. **pegawai/catalog/create.blade.php & edit.blade.php**
   - Form film (title, genre, synopsis, etc)
   - Upload poster
   - Input rental price, stock
   - Checkbox is_available

8. **pegawai/reports/index.blade.php**
   - Statistics cards
   - Popular films chart
   - Monthly revenue chart
   - Links to detailed reports

9. **pegawai/reports/transactions.blade.php**
   - List rentals dengan filters
   - Date range picker
   - Status filter
   - Total revenue summary
   - Export buttons (PDF, CSV)

10. **pegawai/reports/pdf.blade.php**
    - Simple table untuk PDF
    - Header dengan logo & date
    - Transaction list
    - Footer dengan total

### Step 3: Buat Views Owner
1. **owner/dashboard.blade.php**
   - Advanced statistics
   - Revenue charts (monthly, yearly)
   - User & film statistics
   - Popular films chart
   - Recent activities

2. **owner/promos/index.blade.php**
   - List promos dengan status badges
   - Show code, name, type, value, dates
   - Usage count vs limit
   - Actions: edit, delete, toggle status

3. **owner/promos/create.blade.php & edit.blade.php**
   - Form promo (code, name, description)
   - Select type (percentage/fixed)
   - Input value, min_transaction
   - Date pickers (start, end)
   - Input usage_limit
   - Checkbox is_active

4. **owner/users/index.blade.php**
   - List users dengan role badges
   - Search by name/email
   - Filter by role
   - Show name, email, role, phone
   - Actions: edit, delete, change role

5. **owner/users/create.blade.php & edit.blade.php**
   - Form user (name, email, phone, address)
   - Select role
   - Input password (optional di edit)

6. **owner/audit-logs/index.blade.php**
   - List logs dengan filters
   - Filter by action, model, user, date
   - Show timestamp, user, action, description
   - Link to detail

7. **owner/audit-logs/show.blade.php**
   - Detail log lengkap
   - Show old values & new values (JSON)
   - Show IP address & user agent

## Testing Checklist

### User Flow
- [ ] Register & login
- [ ] Browse films
- [ ] Filter by genre
- [ ] Search film
- [ ] View detail film
- [ ] Add to cart
- [ ] Update cart
- [ ] Remove from cart
- [ ] Checkout dengan promo
- [ ] Upload bukti pembayaran
- [ ] View my rentals
- [ ] Extend rental
- [ ] Return film
- [ ] Submit review

### Pegawai Flow
- [ ] Login sebagai pegawai
- [ ] View dashboard
- [ ] Verify payment
- [ ] Reject payment
- [ ] Process return
- [ ] Extend rental
- [ ] Handle overdue
- [ ] Add new film
- [ ] Edit film
- [ ] Delete film
- [ ] View reports
- [ ] Export PDF
- [ ] Export CSV

### Owner Flow
- [ ] Login sebagai owner
- [ ] View dashboard
- [ ] Create promo
- [ ] Edit promo
- [ ] Delete promo
- [ ] Toggle promo status
- [ ] Create user
- [ ] Edit user
- [ ] Change user role
- [ ] Delete user
- [ ] View audit logs
- [ ] Filter audit logs

## Dependencies yang Perlu Diinstall

```bash
composer require barryvdh/laravel-dompdf
```

## Tips Membuat Views

### 1. Gunakan Component Tailwind yang Konsisten
- Buttons: `bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700`
- Inputs: `w-full px-4 py-2 border rounded focus:outline-none focus:border-indigo-500`
- Cards: `bg-white rounded-lg shadow-md p-6`
- Badges: `px-2 py-1 text-xs rounded-full`

### 2. Status Badge Colors
```blade
@switch($rental->status)
    @case('pending')
        <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">Pending</span>
        @break
    @case('active')
        <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Active</span>
        @break
    @case('extended')
        <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">Extended</span>
        @break
    @case('returned')
        <span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-800">Returned</span>
        @break
    @case('overdue')
        <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">Overdue</span>
        @break
@endswitch
```

### 3. Format Currency
```blade
Rp {{ number_format($amount, 0, ',', '.') }}
```

### 4. Format Date
```blade
{{ $date->format('d M Y') }}
{{ $date->format('d M Y H:i') }}
```

### 5. Alpine.js untuk Dropdown
```blade
<div x-data="{ open: false }">
    <button @click="open = !open">Toggle</button>
    <div x-show="open" @click.away="open = false">
        Content
    </div>
</div>
```

## Deployment Checklist

- [ ] Set APP_ENV=production
- [ ] Set APP_DEBUG=false
- [ ] Generate secure APP_KEY
- [ ] Configure database
- [ ] Run migrations
- [ ] Run seeders
- [ ] Create storage link
- [ ] Build assets (npm run build)
- [ ] Set file permissions
- [ ] Configure web server (Apache/Nginx)
- [ ] Enable HTTPS
- [ ] Backup database

## Contact & Support

Sistem ini sudah 80% selesai dengan:
- ✅ Database structure lengkap
- ✅ All models dengan relationships & methods
- ✅ All controllers dengan business logic
- ✅ All routes dengan middleware
- ✅ Main layout & navigation
- ✅ Authentication views

Yang perlu dilengkapi hanya views (UI) untuk menampilkan data yang sudah ada.

Semua backend logic sudah siap, tinggal buat frontend untuk interaksi user!
