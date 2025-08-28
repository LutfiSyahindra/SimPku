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

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/dashboard', function () {
    return view('SIMRS.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware('auth')->group(function () {

    Route::prefix('simrs')->group(function () {

         // Wagateway
        Route::get('/waGateway/wa', [QrCodeController::class, 'index']);
        Route::get('/wa-qr-view', [QrCodeController::class, 'showQrPage'])->name('wa-qr-view');
        Route::get('/wa-qr-fetch', [QrCodeController::class, 'fetchQr'])->name('wa-qr-fetchQr');
        Route::post('/qr/receive', [QrCodeController::class, 'receiveQr']);
        Route::get('/qrcode', [QrCodeController::class, 'show']);
        Route::get('/waGetway/Dashboard', [DashboardWaController::class, 'index']);
        Route::get('/waGetway/terkirim', [DashboardWaController::class, 'widgetTerkirim']);
        Route::get('/waGetway/terjadwal', [DashboardWaController::class, 'widgetTerjadwal']);
        Route::get('/waGetway/gagal', [DashboardWaController::class, 'widgetGagal']);
        Route::get('/waGetway/belum', [DashboardWaController::class, 'widgetBelum']);
        Route::get('/waGetway/batal', [DashboardWaController::class, 'widgetBatal']);
        Route::get('/waGetway/tabledata', [DashboardWaController::class, 'tabelData']);
        Route::get('/waGetway/LaporanWa', [DashboardWaController::class, 'laporanWa']);
        Route::get('/waGetway/log/{id}', [DashboardWaController::class, 'detailLog']);

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

        // It
        Route::get('/khususIt/index', [ItController::class, 'index'])->name('khususIt.index');
        Route::get('/khususIt/laporan', [ItController::class, 'viewLaporan'])->name('khususIt.laporan');
        Route::get('/khususIt/laporan/table', [ItController::class, 'table'])->name('khususIt.laporan.table');
        Route::post('/khususIt/laporan/store', [ItController::class, 'store'])->name('khususIt.laporan.store');
        Route::get('/khususIt/laporan/{id}/edit', [ItController::class, 'edit'])->name('khususIt.laporan.edit');
        Route::put('/khususIt/laporan/{id}/update', [ItController::class, 'update'])->name('khususIt.laporan.update');
        Route::delete('/khususIt/laporan/{id}/delete', [ItController::class, 'destroy'])->name('khususIt.laporan.delete');

        Route::get('/khususIt/widget/pengaduan', [ItController::class, 'widgetPengaduan'])->name('khususIt.widget.pengaduan');
        Route::get('/khususIt/widget/restime', [ItController::class, 'widgetAverageResponseTime'])->name('khususIt.widget.restime');
        Route::get('/khususIt/widget/donetime', [ItController::class, 'widgetAverageCompletionTime'])->name('khususIt.widget.donetime');

        // TaskId
        Route::get('/taskId/index', [taskIdController::class, 'index'])->name('taskId.index');
        Route::get('/taskId/table', [TaskIdController::class, 'listTaskId'])->name('taskId.table');
        Route::get('/taskId/getTaskId', [TaskIdController::class, 'getTaskId'])->name('taskId.getTaskId');
        Route::get('/taskId/rataAdmisi', [TaskIdController::class, 'rataAdmisi'])->name('taskId.rataAdmisi');
        Route::get('/taskId/rataPoli', [TaskIdController::class, 'rataPoli'])->name('taskId.rataPoli');
        Route::get('/taskId/rataFarmasi', [TaskIdController::class, 'rataFarmasi'])->name('taskId.rataFarmasi');
        Route::get('/taskId/dataTaskId', [TaskIdController::class, 'dataTaskId'])->name('taskId.dataTaskId');
        Route::get('/taskId/detailTaskid', [TaskIdController::class, 'detailTaskid'])->name('taskId.detailTaskid');
        Route::get('/taskId/taskIdOnsite', [TaskIdController::class, 'taskIdOnsite'])->name('taskId.taskIdOnsite');
        Route::get('/taskId/taskIdMjkn', [TaskIdController::class, 'taskIdMjkn'])->name('taskId.taskIdMjkn');
        Route::get('/taskId/logTaskId', [TaskIdController::class, 'logTaskId'])->name('taskId.logTaskId');

        // Route::post('/taskId/store', [TaskIdController::class, 'store'])->name('taskId.store');
        // Route::get('/taskId/{id}/edit', [TaskIdController::class, 'edit'])->name('taskId.edit');
        // Route::put('/taskId/{id}/update', [TaskIdController::class, 'update'])->name('taskId.update');
        // Route::delete('/taskId/{id}/delete', [TaskIdController::class, 'destroy'])->name('taskId.delete');

        // Surat SDI
        Route::get('/surat/kategori/index', [MasterSuratController::class, 'index'])->name('surat.kategori.index');
        Route::get('/surat/kategori/table', [MasterSuratController::class, 'table'])->name('surat.kategori.table');
        Route::post('/surat/kategori/store', [MasterSuratController::class, 'store'])->name('surat.kategori.store');
        Route::get('/surat/kategori/{id}/edit', [MasterSuratController::class, 'edit'])->name('surat.kategori.edit');
        Route::put('/surat/kategori/{id}/update', [MasterSuratController::class, 'update'])->name('surat.kategori.update');
        Route::delete('/surat/kategori/{id}/destroy', [MasterSuratController::class, 'destroy'])->name('surat.kategori.destroy');

        // Surat Masuk SDI
        Route::get('/surat/masuk/index', [SuratMasukController::class, 'index'])->name('surat.masuk.index');
        Route::get('/surat/masuk/table', [SuratMasukController::class, 'table'])->name('surat.masuk.table');
        Route::post('/surat/masuk/store', [SuratMasukController::class, 'store'])->name('surat.masuk.store');
        Route::get('/surat/masuk/{id}/edit', [SuratMasukController::class, 'edit'])->name('surat.masuk.edit');
        Route::put('/surat/masuk/{id}/update', [SuratMasukController::class, 'update'])->name('surat.masuk.update');
        Route::delete('/surat/masuk/{id}/destroy', [SuratMasukController::class, 'destroy'])->name('surat.masuk.destroy');

    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::prefix('api')->group(function () {
    require base_path('routes/api.php');
});
require __DIR__.'/auth.php';

