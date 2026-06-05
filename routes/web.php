<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});
Route::get('/ui', function () {
    return view('pages.dashboard.ecommerce');
});

Route::get('/dashboard', function (Illuminate\Http\Request $request) {
    $role = $request->user()->role->nama_role;
    if ($role === 'superadmin') {
        return redirect()->route('superadmin.dashboard');
    } elseif ($role === 'admin') {
        return redirect()->route('admin.dashboard');
    } else {
        return redirect()->route('nasabah.dashboard');
    }
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified', 'role:superadmin'])->group(function () {
    Route::get('/superadmin/dashboard', [\App\Http\Controllers\Superadmin\DashboardController::class, 'index'])->name('superadmin.dashboard');

    Route::get('/superadmin/nasabah', [\App\Http\Controllers\Superadmin\NasabahController::class, 'index'])->name('superadmin.nasabah');
    Route::put('/superadmin/nasabah/{user}/toggle', [\App\Http\Controllers\Superadmin\NasabahController::class, 'toggleStatus'])->name('superadmin.nasabah.toggle');
    Route::delete('/superadmin/nasabah/{user}', [\App\Http\Controllers\Superadmin\NasabahController::class, 'destroy'])->name('superadmin.nasabah.destroy');

    Route::get('/superadmin/admin', [\App\Http\Controllers\Superadmin\AdminController::class, 'index'])->name('superadmin.admin');
    Route::post('/superadmin/admin', [\App\Http\Controllers\Superadmin\AdminController::class, 'store'])->name('superadmin.admin.store');
    Route::put('/superadmin/admin/{user}', [\App\Http\Controllers\Superadmin\AdminController::class, 'update'])->name('superadmin.admin.update');
    Route::put('/superadmin/admin/{user}/toggle', [\App\Http\Controllers\Superadmin\AdminController::class, 'toggleStatus'])->name('superadmin.admin.toggle');
    Route::delete('/superadmin/admin/{user}', [\App\Http\Controllers\Superadmin\AdminController::class, 'destroy'])->name('superadmin.admin.destroy');

    Route::get('/superadmin/laporan/transaksi', [\App\Http\Controllers\Superadmin\LaporanController::class, 'transaksi'])->name('superadmin.laporan.transaksi');
    Route::get('/superadmin/laporan/saldo', [\App\Http\Controllers\Superadmin\LaporanController::class, 'saldo'])->name('superadmin.laporan.saldo');
    Route::get('/superadmin/laporan/export-pdf', [\App\Http\Controllers\Superadmin\LaporanController::class, 'exportPdf'])->name('superadmin.laporan.export-pdf');

    Route::get('/superadmin/pengaturan', [\App\Http\Controllers\Superadmin\PengaturanController::class, 'index'])->name('superadmin.pengaturan');
    Route::put('/superadmin/pengaturan/profile', [\App\Http\Controllers\Superadmin\PengaturanController::class, 'updateProfile'])->name('superadmin.pengaturan.profile');
    Route::put('/superadmin/pengaturan/password', [\App\Http\Controllers\Superadmin\PengaturanController::class, 'updatePassword'])->name('superadmin.pengaturan.password');

    Route::get('/superadmin/activity-log', [\App\Http\Controllers\Superadmin\ActivityLogController::class, 'index'])->name('superadmin.activity-log');
});

Route::middleware(['auth', 'verified', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('admin.dashboard');

    Route::get('/admin/nasabah', [\App\Http\Controllers\Admin\NasabahController::class, 'index'])->name('admin.nasabah');
    Route::post('/admin/nasabah', [\App\Http\Controllers\Admin\NasabahController::class, 'store'])->name('admin.nasabah.store');
    Route::put('/admin/nasabah/{user}', [\App\Http\Controllers\Admin\NasabahController::class, 'update'])->name('admin.nasabah.update');

    Route::get('/admin/tabungan', [\App\Http\Controllers\Admin\TabunganController::class, 'index'])->name('admin.tabungan');
    Route::post('/admin/tabungan/setor', [\App\Http\Controllers\Admin\TabunganController::class, 'setor'])->name('admin.tabungan.setor');
    Route::post('/admin/tabungan/tarik', [\App\Http\Controllers\Admin\TabunganController::class, 'tarik'])->name('admin.tabungan.tarik');
    Route::get('/admin/tabungan/riwayat/{user}', [\App\Http\Controllers\Admin\TabunganController::class, 'riwayat'])->name('admin.tabungan.riwayat');
    Route::get('/admin/riwayat', [\App\Http\Controllers\Admin\TabunganController::class, 'semuaRiwayat'])->name('admin.riwayat');
});

Route::middleware(['auth', 'verified', 'role:nasabah'])->group(function () {
    Route::get('/nasabah/dashboard', function () {
        return redirect()->route('nasabah.home');
    })->name('nasabah.dashboard');

    Route::get('/nasabah/home', [\App\Http\Controllers\Nasabah\HomeController::class, 'index'])->name('nasabah.home');
    Route::get('/nasabah/riwayat', [\App\Http\Controllers\Nasabah\HomeController::class, 'riwayat'])->name('nasabah.riwayat');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
