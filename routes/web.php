<?php

use App\Models\PenjualanDetail;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\PembelianController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\PengeluaranController;
use App\Http\Controllers\PembelianDetailController;
use App\Http\Controllers\PenjualanDetailController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\UserController;

Route::get('/', fn () => redirect()->route('login'));

Route::get('/register', function () {
    abort(403, 'Unauthorized action.');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', [HomeController::class, 'index'])->name('home');
});

Route::group(['middlware' => 'auth'], function() {
    Route::get('/kategori/data', [KategoriController:: class, 'data'])->name('kategori-data');
    Route::resource('/kategori', KategoriController:: class);

    Route::get('/produk/data', [ProdukController:: class, 'data'])->name('produk-data');
    Route::post('/produk/delete-selected', [ProdukController:: class, 'deleteSelected'])->name('produk.delete-selected');
    Route::post('/produk/cetak-barcode', [ProdukController:: class, 'cetakBarcode'])->name('produk.cetak-barcode');
    Route::resource('/produk', ProdukController:: class);

    Route::get('/member/data', [MemberController:: class, 'data'])->name('member-data');
    Route::post('/produk/cetak-member', [MemberController:: class, 'cetakMember'])->name('member.cetak-member');
    Route::resource('/member', MemberController:: class);

    Route::get('/supplier/data', [SupplierController:: class, 'data'])->name('supplier-data');
    Route::resource('/supplier', SupplierController:: class);

    Route::get('/pengeluaran/data', [PengeluaranController:: class, 'data'])->name('pengeluaran-data');
    Route::resource('/pengeluaran', PengeluaranController:: class);

    Route::get('/pembelian/data', [PembelianController:: class, 'data'])->name('pembelian-data');
    Route::get('/pembelian/{id}/create', [PembelianController:: class, 'create'])->name('pembelian.create');
    Route::get('/pembelian/invoice/{id}', [PembelianController::class, 'cetak_invoice'])->name('pembelian.cetak-invoice');
    Route::resource('/pembelian', PembelianController:: class)->except('create');

    Route::get('/pembelian_detail/{id}/data', [PembelianDetailController:: class, 'data'])->name('pembelian_detail-data');
    Route::get('/pembelian_detail/load_form/{diskon}/{total}', [PembelianDetailController:: class, 'load_form'])->name('pembelian_detail.load_form');
    Route::resource('/pembelian_detail', PembelianDetailController:: class)->except('create', 'show', 'edit');

    Route::get('/penjualan/data', [PenjualanController::class, 'data'])->name('penjualan-data');
    Route::get('/penjualan/nota/{id}', [PenjualanController::class, 'cetak_nota'])->name('penjualan.cetak-nota');
    Route::resource('/penjualan', PenjualanController:: class)->except('edit');

    Route::get('/transaksi/baru', [PenjualanController::class, 'create'])->name('transaksi.baru');
    Route::post('/transaksi/simpan', [PenjualanController::class, 'store'])->name('transaksi.simpan');

    Route::get('/transaksi/{id}/data', [PenjualanDetailController::class, 'data'])->name('transaksi-data');
    Route::get('/transaksi/load_form/{diskon}/{total}/{diterima}', [PenjualanDetailController::class, 'load_form'])->name('transaksi.load_form');
    Route::resource('/transaksi', PenjualanDetailController:: class)->except('show');

    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/data/{awal}/{akhir}', [LaporanController::class, 'data'])->name('laporan-data');
    Route::get('/laporan/pdf/{awal}/{akhir}', [LaporanController::class, 'export_pdf'])->name('laporan.export_pdf');

    Route::get('/user/data', [UserController::class, 'data'])->name('user-data');
    Route::resource('/user', UserController::class);

    Route::get('/setting', [SettingController::class, 'index'])->name('setting.index');
    Route::get('/setting/first', [SettingController::class, 'show'])->name('setting.show');
    Route::post('/setting', [SettingController::class, 'update'])->name('setting.update');

    Route::get('/profil', [UserController::class, 'profil'])->name('user.profil');
    Route::post('/profil', [UserController::class, 'update_profil'])->name('user.update_profil');
});
