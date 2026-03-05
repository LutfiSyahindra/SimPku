<?php

use App\Http\Controllers\Api\QrCodeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\simrs\Anjungan\admisiController;
use App\Http\Controllers\simrs\Anjungan\AnjunganController;
use App\Http\Controllers\simrs\Anjungan\antrianFarmasiController;
use App\Http\Controllers\simrs\display\apotekwsController;
use App\Http\Controllers\simrs\display\KasirController;
use App\Http\Controllers\simrs\display\KasirKhnzaController;
use App\Http\Controllers\simrs\display\PippController;
use App\Http\Controllers\simrs\display\PoliController;
use App\Http\Controllers\simrs\display\PoliWsController;
use App\Http\Controllers\simrs\Dokumen\CetakDokumenLengkapController;
use App\Http\Controllers\simrs\It\ItController;
use App\Http\Controllers\simrs\PetugasPanggil\kasirPanggilController;
use App\Http\Controllers\simrs\PetugasPanggil\pippPanggilController;
use App\Http\Controllers\simrs\PetugasPanggil\poliPanggilController;
use App\Http\Controllers\simrs\Surat\MasterSuratController;
use App\Http\Controllers\simrs\Surat\SuratMasukController;
use App\Http\Controllers\simrs\taskId\taskIdController;
use App\Http\Controllers\simrs\Users\permissionsController;
use App\Http\Controllers\simrs\Users\rolesController;
use App\Http\Controllers\simrs\Users\UsersController;
use App\Http\Controllers\simrs\waGateway\DashboardWaController;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


    // Anjungan
    Route::get('simrs/anjungan/index', [AnjunganController::class, 'index'])->name('anjungan.index');
    // Admisi
    Route::post('simrs/anjungan/admisi/generateNoAntrian', [admisiController::class, 'generateAntrianAdmisi'])->name('anjungan.admisi.generateNoAntrian');        
    Route::get('simrs/anjungan/admisi/cetakAntrian/{nomor}', [admisiController::class, 'cetakAntrian'])->name('anjungan.admisi.cetakAntrian');
    // Antrian Farmasi
    Route::post('simrs/anjungan/antrianFarmasi/generateNoAntrianFarmasi', [antrianFarmasiController::class, 'generateAntrian'])->name('anjungan.antrianFarmasi.generateAntrian');
    Route::get('simrs/anjungan/antriFarmasi/cetakAntrian/{nomor}', [antrianFarmasiController::class, 'cetakAntrian'])->name('anjungan.antrianFarmasi.cetakAntrian');

    // Dokumen
    Route::get('simrs/report/ranap', [CetakDokumenLengkapController::class, 'cetak'])->name('report.ranap');   
    Route::get('simrs/report/ranap/resume', [CetakDokumenLengkapController::class, 'cetakResume'])->name('report.ranap.resume');   

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/dashboard', function () {
    return view('SIMRS.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware('auth')->group(function () {

    Route::prefix('simrs')->group(function () {

        // Display Poli
        Route::get('/display/poli', [PoliController::class, 'index'])->name('display.poli');
        Route::get('/display/poli/data', [PoliController::class, 'data'])->name('display.poli.data');
        Route::get('/display/poli/lastdata', [PoliController::class, 'lastdata'])->name('display.poli.lastdata');
        Route::get('/display/poliws', [PoliWsController::class, 'index'])->name('display.poliws');

        // Display Apotek
        Route::get('/display/apotek', [apotekwsController::class, 'index'])->name('display.apotek');
        Route::get('/display/panggil-apotek', [apotekwsController::class, 'panggilAntrean'])->name('display.panggilapotek');
        Route::put('/display/update-apotek', [apotekwsController::class, 'updateAntrean'])->name('display.updateapotek');
        Route::get('/display/nonracikan', [apotekwsController::class, 'dataNonracikan'])->name('display.nonracikan');
        Route::get('/display/racikan', [apotekwsController::class, 'dataracikan'])->name('display.racikan');

        // petugasPanggilPoli
        Route::get('/petugasPanggil/poliPanggil', [poliPanggilController::class, 'index'])->name('petugasPanggil.poliPanggil');
        Route::get('/petugasPanggil/getPoli', [poliPanggilController::class, 'getDataPoli'])->name('petugasPanggil.getPoli');
        Route::get('/petugasPanggil/getPasien', [poliPanggilController::class, 'getDataPasien'])->name('petugasPanggil.getPasien');
        Route::get('/petugasPanggil/getDokter', [poliPanggilController::class, 'getDataDokter'])->name('petugasPanggil.getDokter');
        Route::post('/petugasPanggil/panggilPasien', [poliPanggilController::class, 'panggilPasien'])->name('petugasPanggil.panggilPasien');

        // Display PIPP
        Route::get('/display/pipp', [PippController::class, 'index'])->name('display.pipp');

        // Display Kasir
        Route::get('/display/kasir', [KasirController::class, 'index'])->name('display.kasir');  
        Route::get('/display/kasirKahnza', [KasirKhnzaController::class, 'index'])->name('display.khanza.kasir');  
        Route::get('/display/kasirKahnza/panggil', [KasirKhnzaController::class, 'panggilAntrean'])->name('display.khanza.kasir.panggil');  
        Route::put('/display/kasirKahnza/update', [KasirKhnzaController::class, 'updateAntrean'])->name('display.khanza.kasir.update');  

        // Petugas Panggil Pipp
        Route::get('/petugasPanggil/pipp/pippPanggil', [pippPanggilController::class, 'index'])->name('petugasPanggil.pipp.pippPanggil');
        Route::get('/petugasPanggil/pipp/pippPanggil/dataPasien', [pippPanggilController::class, 'getDataPasien'])->name('petugasPanggil.pipp.pippPanggil.dataPasien');
        Route::post('/petugasPanggil/pipp/pippPanggil/panggilPipp', [pippPanggilController::class, 'panggilPipp'])->name('petugasPanggil.pipp.pippPanggil.panggilPipp');

        // Petugas Panggil Kasir
        Route::get('/petugasPanggil/kasir/kasirPanggil', [kasirPanggilController::class, 'index'])->name('petugasPanggil.kasir.kasirPanggil');
        Route::get('/petugasPanggil/kasir/kasirPanggil/dataPasien', [kasirPanggilController::class, 'getDataPasien'])->name('petugasPanggil.kasir.kasirPanggil.dataPasien');
        Route::post('/petugasPanggil/kasir/kasirPanggil/panggilKasir', [kasirPanggilController::class, 'panggilKasir'])->name('petugasPanggil.kasir.kasirPanggil.panggilKasir');

        // Users
        Route::get('/users/index', [UsersController::class, 'index'])->name('users.index');
        Route::get('/users/table', [UsersController::class, 'table'])->name('users.table');
        Route::post('/users/store', [UsersController::class, 'store'])->name('users.store');
        Route::get('/users/{id}/edit', [UsersController::class, 'edit'])->name('users.edit');
        Route::put('/users/{id}/update', [UsersController::class, 'update'])->name('users.update');
        Route::delete('/users/{id}/delete', [UsersController::class, 'destroy'])->name('users.delete');
        Route::get('/users/roles/list', [UsersController::class, 'listRoles'])->name('users.roles.list');
        Route::get('/users/{id}/roles', [UsersController::class, 'getUserRoles'])->name('usersRoles.roles');
        Route::post('/users/{userId}/rolesAttach', [UsersController::class, 'attachRoles'])->name('users.assign.roles');

        // Roles
        Route::get('/roles/index', [rolesController::class, 'index'])->name('roles.index');
        Route::get('/roles/table', [rolesController::class, 'table'])->name('roles.table');
        Route::post('/roles/store', [rolesController::class, 'store'])->name('roles.store');
        Route::get('/roles/{id}/edit', [rolesController::class, 'edit'])->name('roles.edit');
        Route::put('/roles/{id}/update', [rolesController::class, 'update'])->name('roles.update');
        Route::delete('/roles/{id}/delete', [rolesController::class, 'destroy'])->name('roles.delete');
        Route::get('/roles/permissions/list', [rolesController::class, 'listPermissions'])->name('permissions.list');
        Route::get('/roles/{id}/permissions', [rolesController::class, 'getRolePermissions'])->name('roles.permissions');
        Route::post('/roles/{roleId}/permissionsAttach', [rolesController::class, 'attachPermissions'])->name('roles.assign.permissions');

        // Permissions
        Route::get('/permissions/index', [permissionsController::class, 'index'])->name('permissions.index');
        Route::get('/permissions/table', [permissionsController::class, 'table'])->name('permissions.table');
        Route::post('/permissions/store', [permissionsController::class, 'store'])->name('permissions.store');
        Route::get('/permissions/{id}/edit', [permissionsController::class, 'edit'])->name('permissions.edit');
        Route::put('/permissions/{id}/update', [permissionsController::class, 'update'])->name('permissions.update');
        Route::delete('/permissions/{id}/delete', [permissionsController::class, 'destroy'])->name('permissions.delete');

    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::prefix('api')->group(function () {
    require base_path('routes/api.php');
});
require __DIR__.'/auth.php';

