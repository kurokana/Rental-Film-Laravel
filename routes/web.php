<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FilmController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\RentalController;
use App\Http\Controllers\ReviewController;

// Pegawai Controllers
use App\Http\Controllers\Pegawai\DashboardController as PegawaiDashboard;
use App\Http\Controllers\Pegawai\PaymentVerificationController;
use App\Http\Controllers\Pegawai\RentalManagementController;
use App\Http\Controllers\Pegawai\CatalogController as PegawaiCatalog;
use App\Http\Controllers\Pegawai\ReportController as PegawaiReport;

// Owner Controllers
use App\Http\Controllers\Owner\DashboardController as OwnerDashboard;
use App\Http\Controllers\Owner\PromoController;
use App\Http\Controllers\Owner\UserRoleController;
use App\Http\Controllers\Owner\AuditLogController;
use App\Http\Controllers\Owner\ReportController as OwnerReport;

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/films', [FilmController::class, 'index'])->name('films.index');
Route::get('/films/{slug}', [FilmController::class, 'show'])->name('films.show');

// Auth Routes
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// User Routes (Role: user)
Route::middleware(['auth', 'role:user'])->group(function () {
    // Cart
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart', [CartController::class, 'store'])->name('cart.store');
    Route::put('/cart/{cart}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{cart}', [CartController::class, 'destroy'])->name('cart.destroy');

    // Rentals
    Route::get('/my-rentals', [RentalController::class, 'myRentals'])->name('rentals.my-rentals');
    Route::get('/checkout', [RentalController::class, 'checkout'])->name('rentals.checkout');
    Route::post('/checkout', [RentalController::class, 'processCheckout'])->name('rentals.process-checkout');
    Route::get('/payment/{payment}', [RentalController::class, 'showPayment'])->name('rentals.payment');
    Route::put('/payment/{payment}/upload', [RentalController::class, 'uploadProof'])->name('rentals.upload-proof');
    Route::post('/rentals/{rental}/extend', [RentalController::class, 'extend'])->name('rentals.extend');
    Route::post('/rentals/{rental}/return', [RentalController::class, 'return'])->name('rentals.return');
    Route::put('/rentals/{rental}/cancel', [RentalController::class, 'cancel'])->name('rentals.cancel');
    Route::get('/rentals/{rental}/invoice', [RentalController::class, 'invoice'])->name('rentals.invoice');
    Route::post('/check-promo', [RentalController::class, 'checkPromo'])->name('rentals.check-promo');

    // Reviews
    Route::get('/rentals/{rental}/review', [ReviewController::class, 'create'])->name('reviews.create');
    Route::post('/rentals/{rental}/review', [ReviewController::class, 'store'])->name('reviews.store');
});

// Pegawai Routes (Role: pegawai, owner)
Route::prefix('pegawai')->name('pegawai.')->middleware(['auth', 'role:pegawai,owner'])->group(function () {
    Route::get('/dashboard', [PegawaiDashboard::class, 'index'])->name('dashboard');

    // Payment Verification
    Route::get('/payments', [PaymentVerificationController::class, 'index'])->name('payments.index');
    Route::get('/payments/{payment}', [PaymentVerificationController::class, 'show'])->name('payments.show');
    Route::post('/payments/{payment}/verify', [PaymentVerificationController::class, 'verify'])->name('payments.verify');
    Route::post('/payments/{payment}/reject', [PaymentVerificationController::class, 'reject'])->name('payments.reject');

    // Rental Management
    Route::get('/rentals', [RentalManagementController::class, 'index'])->name('rentals.index');
    Route::get('/rentals/{rental}', [RentalManagementController::class, 'show'])->name('rentals.show');
    Route::post('/rentals/{rental}/return', [RentalManagementController::class, 'processReturn'])->name('rentals.return');
    Route::post('/rentals/{rental}/extend', [RentalManagementController::class, 'extendRental'])->name('rentals.extend');
    Route::post('/rentals/{rental}/overdue', [RentalManagementController::class, 'handleOverdue'])->name('rentals.overdue');
    Route::post('/rentals/{rental}/cancel', [RentalManagementController::class, 'cancelRental'])->name('rentals.cancel');

    // Catalog Management
    Route::resource('catalog', PegawaiCatalog::class);

    // Reports
    Route::get('/reports', [PegawaiReport::class, 'index'])->name('reports.index');
    Route::get('/reports/transactions', [PegawaiReport::class, 'transactions'])->name('reports.transactions');
    Route::get('/reports/export-pdf', [PegawaiReport::class, 'exportPdf'])->name('reports.export-pdf');
    Route::get('/reports/export-csv', [PegawaiReport::class, 'exportCsv'])->name('reports.export-csv');
});

// Owner Routes (Role: owner only)
Route::prefix('owner')->name('owner.')->middleware(['auth', 'role:owner'])->group(function () {
    Route::get('/dashboard', [OwnerDashboard::class, 'index'])->name('dashboard');

    // Promo Management
    Route::resource('promos', PromoController::class);
    Route::post('/promos/{promo}/toggle-status', [PromoController::class, 'toggleStatus'])->name('promos.toggle-status');

    // User & Role Management
    Route::resource('users', UserRoleController::class);
    Route::post('/users/{user}/change-role', [UserRoleController::class, 'changeRole'])->name('users.change-role');

    // Audit Logs
    Route::get('/audit-logs', [AuditLogController::class, 'index'])->name('audit-logs.index');
    Route::get('/audit-logs/{auditLog}', [AuditLogController::class, 'show'])->name('audit-logs.show');

    // Reports & Export
    Route::get('/reports', [OwnerReport::class, 'index'])->name('reports.index');
    Route::get('/reports/export', [OwnerReport::class, 'export'])->name('reports.export');
});
