<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
 */

Route::get('/', 'HomeController');

Route::group(['middleware' => ['auth:api', 'verified']], function () {
    Route::post('logout', 'Auth\LoginController@logout');

    Route::get('/user', 'Settings\ProfileController@index');

    Route::post('pcr/add', 'V1\PCRController@input_hasil_pemeriksaan');
    Route::patch('settings/profile', 'Settings\ProfileController@update');
    Route::patch('settings/password', 'Settings\PasswordController@update');

    Route::get('/pengguna', 'PenggunaController@listPengguna');
    Route::post('/pengguna', 'PenggunaController@savePengguna');
    Route::post('/pengguna/{id}', 'PenggunaController@updatePengguna');
    Route::delete('/pengguna/{id}', 'PenggunaController@deletePengguna');
    Route::get('/pengguna/{id}', 'PenggunaController@showUpdate');
    Route::get('/lab-satelit', 'LabSatelitController@listLabSatelit');
    Route::post('/lab-satelit', 'LabSatelitController@saveLabSatelit');
    Route::post('/lab-satelit/{id}', 'LabSatelitController@updateLabSatelit');
    Route::delete('/lab-satelit/{id}', 'LabSatelitController@deleteLabSatelit');
    Route::get('/lab-satelit/{id}', 'LabSatelitController@showUpdate');
    Route::get('roles-option', 'OptionController@getRoles');
    Route::get('lab-pcr-option', 'OptionController@getLabPCR');
    Route::get('lab-satelit-option', 'OptionController@getLabSatelit');
    Route::get('jenis-sampel-option', 'OptionController@getJenisSampel');
    Route::get('validator-option', 'OptionController@getValidator');

    Route::group(['prefix' => 'registrasi-sampel'], function () {
        Route::get('/', 'Registrasisampel@getData');
        Route::get('/export-excel', 'Registrasisampel@exportExcel');
    });

    Route::group(['prefix' => 'pemeriksaansampel'], function () {
        Route::get('/get-data', 'PemeriksaanSampleController@getData');
        Route::get('/get-dikirim', 'PemeriksaanSampleController@getDikirim');
    });

    Route::group(['prefix' => 'lab-satelit'], function () {
        Route::get('/', 'LabSatelitController@listLabsatelit');
    });
});

Route::group(['middleware' => ['guest:api']], function () {
    Route::post('login', 'Auth\LoginController@login');
    Route::post('/v1/user/register', 'V1\UserController@register')->name('api.user.register');
    Route::get('/v1/user/register/{invite:token}', 'V1\UserController@tokenInfo')->name('api.user.tokenInfo');
    Route::post('/v1/user/dinkes/register', 'V1\User\DinkesController@register')->name('api.users.dinkes.register');
    Route::post('/v1/user/lab/register', 'V1\User\LabController@register')->name('api.users.Lab.register');
});

Route::group(['middleware' => 'auth:api', 'namespace' => 'V1', 'prefix' => 'v1'], function () {

    Route::group(['prefix' => 'dashboard'], function () {
        Route::get('/tracking', 'DashboardController@tracking');
        Route::get('/pasien-diperiksa', 'DashboardController@pasienDiperiksa');
        Route::get('/ekstraksi', 'DashboardController@ekstraksi');
        Route::get('/registrasi', 'DashboardController@registrasi');
        Route::get('/pcr', 'DashboardController@pcr');
        Route::get('/notifications', 'DashboardController@notifications');
        Route::get('/positif-negatif', 'DashboardController@positifNegatif');
        Route::get('/instansi-pengirim', 'DashboardController@instansi_pengirim');
    });

    Route::group(['prefix' => 'dashboard-admin'], function () {
        Route::get('/tracking', 'DashboardAdminController@tracking');
        Route::get('/chart-hasil-pemeriksaan', 'DashboardAdminController@chartHasilPemeriksaan');
        Route::get('/chart-trendline', 'DashboardAdminController@chartTrendline');
        Route::get('/chart-hasil-pemeriksaan-by-kota', 'DashboardAdminController@chartHasilPemeriksaanByKota');
        Route::get('/chart-register-by-fasyankes', 'DashboardAdminController@chartRegisterByFasyankes');
    });

    Route::group(['prefix' => 'chart'], function () {
        Route::get('/regis-mandiri', 'DashboardController@chartMandiri');
        Route::get('/regis-rujukan', 'DashboardController@chartRujukan');
        Route::get('/positif', 'DashboardController@chartPositif');
        Route::get('/negatif', 'DashboardController@chartNegatif');
        Route::get('/ekstraksi', 'DashboardController@chartEkstraksi');
        Route::get('/pcr', 'DashboardController@chartPcr');
    });

    Route::group(['prefix' => 'pcr'], function () {
        Route::get('/get-data', 'PCRController@getData');
        Route::get('/detail/{id}', 'PCRController@detail');
        Route::post('/edit/{id}', 'PCRController@edit');
        Route::post('/terima', 'PCRController@terima');
        Route::post('/invalid/{id}', 'PCRController@invalid');
        Route::post('/input/{id}', 'PCRController@input');
        Route::post('/input-invalid/{id}', 'PCRController@inputInvalid');
        Route::post('/upload-grafik', 'PCRController@uploadGrafik');
        Route::post('/musnahkan/{id}', 'PCRController@musnahkan');
        Route::post('/import-hasil-pemeriksaan', 'ImportRegisterController@importInputPemeriksaan');
        Route::post('/import-data-hasil-pemeriksaan', 'PCRController@importDataHasilPemeriksaan');
    });

    Route::get('list-negara', 'KotaController@listNegara');
    Route::get('list-provinsi', 'KotaController@listProvinsi');
    Route::get('list-kota/{provinsi}', 'KotaController@listKota');
    Route::get('list-kota-jabar', 'KotaController@listKota');
    Route::get('list-kecamatan/{kota}', 'KotaController@listKecamatan');
    Route::get('list-kelurahan/{kec}', 'KotaController@listKelurahan');

    Route::get('kota/detail/{kota}', 'KotaController@show');

    Route::get('list-fasyankes-jabar', 'FasyankesController@listByProvinsi');
    Route::get('fasyankes/detail/{fasyankes}', 'FasyankesController@show');

    Route::group(['prefix' => 'register'], function () {

        Route::post('sampel', 'RegistersampelController@storesampel');
        Route::delete('sampel-bulk/', 'DeleteSampelBulkController');
        Route::post('sampel/update/{regis_id}/{pasien_id}', 'RegistersampelController@storeUpdate');
        Route::get('sampel/{register_id}/{pasien_id}', 'RegistersampelController@getById');
        Route::delete('sampel/{id}/{pasien}', 'RegistersampelController@delete');
        Route::get('logs/{register_id}', 'RegistersampelController@logs');

        Route::post('import-sampel', 'ImportRegisterController@importRegisterSampel');
        Route::post('import-hasil-pemeriksaan', 'ImportRegisterController@importHasilPemeriksaan');
    });

    Route::group(['prefix' => 'verifikasi'], function () {

        Route::get('list', 'VerifikasiController@index');

        Route::get('export', 'VerifikasiController@export');

        Route::get('list-verified', 'VerifikasiController@indexVerified');

        Route::get('detail/{sampel}', 'VerifikasiController@show');

        Route::get('get-sampel-status', 'VerifikasiController@sampelStatusList');

        Route::post('edit-status-sampel/{sampel}', 'VerifikasiController@updateToVerified');

        Route::post('verifikasi-single-sampel/{sampel}', 'VerifikasiController@verifiedSingleSampel');

        Route::get('list-kategori', 'VerifikasiController@listKategori');
    });

    //pelaporan
    Route::group(['prefix' => 'pelaporan'], function () {
        Route::get('fetch', 'PelaporanController@fetch_data');
    });

    Route::get('download', 'FileDownloadController@download');


    Route::group(['prefix' => 'user'], function () {
        Route::get('/', 'UserController@index')->name('api.user.index');
        Route::get('/{user:id}', 'UserController@show')->name('api.user.show');
        Route::put('/{user:id}', 'UserController@update')->name('api.user.update');
        Route::delete('/{user:id}', 'UserController@delete')->name('api.user.delete');
        Route::post('/invite', 'UserInvitationController')->name('api.user.invite');
        Route::put('/status-toggle/{user:id}', 'UserController@statusToggle')->name('api.user.statusToggle');

        Route::group(['namespace' => 'User'], function () {
            Route::group(['prefix' => 'dinkes'], function () {
                Route::put('/{user:id}', 'DinkesController@update')->name('api.users.dinkes.update');
                Route::post('/', 'DinkesController@store')->name('api.users.dinkes.store');
                Route::post('/invite', 'DinkesController@invite')->name('api.users.dinkes.invite');
            });

            Route::group(['prefix' => 'lab'], function () {
                Route::put('/{user:id}', 'LabController@update')->name('api.users.Lab.update');
                Route::post('/', 'LabController@store')->name('api.users.Lab.store');
                Route::post('/invite', 'LabController@invite')->name('api.users.lab.invite');
            });

            Route::group(['prefix' => 'perujuk'], function () {
                Route::put('/{user:id}', 'PerujukController@update')->name('api.users.perujuk.update');
                Route::post('/', 'PerujukController@store')->name('api.users.perujuk.store');
            });
        });
    });

    Route::group(['prefix' => 'users'], function () {
        Route::get('/{role}', 'ListUserController');
    });

    Route::apiResource('register-perujuk', 'RegisterPerujukController');
    Route::group(['prefix' => 'register-perujuk'], function () {
        Route::post('/bulk', 'RegisterPerujukBulkController');
        Route::post('/import', 'ImportRegisterController@importRegisterPerujuk');
    });
});
