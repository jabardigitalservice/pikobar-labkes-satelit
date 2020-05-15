<?php

use Illuminate\Http\Request;
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

Route::get('/', function() {
	return [
		'App' => 'RESTfulAPI v0.1'
	];
});

Route::group(['middleware' => 'auth:api'], function () {
    Route::post('logout', 'Auth\LoginController@logout');

    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::patch('settings/profile', 'Settings\ProfileController@update');
    Route::patch('settings/password', 'Settings\PasswordController@update');

    Route::group(["prefix" => "sample"], function(){
        Route::get('/get-data', 'SampleController@getData'); 
        Route::post('/add', 'SampleController@add');
        Route::get('/get/{id}','SampleController@getById');
        Route::get('/edit/{id}','SampleController@getUpdate');
        Route::get('/delete/{id}','SampleController@delete');
        Route::post('/update/{id}','SampleController@storeUpdate');
        Route::get('/get-sample/{nomor}','SampleController@getSamples');
    });

    Route::get('/pengguna', 'PenggunaController@listPengguna');
    Route::post('/pengguna', 'PenggunaController@savePengguna');
    Route::post('/pengguna/{id}', 'PenggunaController@updatePengguna');
    Route::delete('/pengguna/{id}','PenggunaController@deletePengguna');
    Route::get('/pengguna/{id}','PenggunaController@showUpdate');
    Route::get('/lab-satelit', 'LabSatelitController@listLabSatelit');
    Route::post('/lab-satelit', 'LabSatelitController@saveLabSatelit');
    Route::post('/lab-satelit/{id}', 'LabSatelitController@updateLabSatelit');
    Route::delete('/lab-satelit/{id}','LabSatelitController@deleteLabSatelit');
    Route::get('/lab-satelit/{id}','LabSatelitController@showUpdate');
    Route::get('roles-option','OptionController@getRoles');
    Route::get('lab-pcr-option','OptionController@getLabPCR');
    Route::get('lab-satelit-option','OptionController@getLabSatelit');
    Route::get('jenis-sampel-option','OptionController@getJenisSampel');
    Route::get('validator-option','OptionController@getValidator');

    Route::group(['prefix'=>'registrasi-mandiri'], function(){
        Route::get('/','RegistrasiMandiri@getData');
        Route::get('/export-excel','RegistrasiMandiri@exportExcel');
    });

    Route::group(['prefix'=>'registrasi-WebSatelit'], function(){
        Route::get('/','RegistrasiWebSatelit@getData');
        Route::get('/export-excel','RegistrasiWebSatelit@exportExcel');
    });

    Route::group(['prefix'=>'registrasi-rujukan'], function(){
        Route::post('/cek','RegistrasiRujukanController@cekData');
        Route::post('/store','RegistrasiRujukanController@store');
        Route::get('/export-excel','RegistrasiMandiri@exportExcel');
        Route::delete('/delete/{id}/{pasien}','RegistrasiRujukanController@delete');

        Route::post('update/{register_ids}/{pasien_id}','RegistrasiRujukanController@storeUpdate');
        Route::get('update/{register_id}/{pasien_id}','RegistrasiRujukanController@getById');
        Route::get('/export-excel','RegistrasiRujukanController@exportExcel');
    });

    Route::group(['prefix'=>'pemeriksaansampel'], function(){
        Route::get('/get-data','PemeriksaanSampleController@getData');
        Route::get('/get-dikirim','PemeriksaanSampleController@getDikirim');
    });
});

Route::group(['middleware' => ['guest:api','cors']], function () {
    
    Route::post('login', 'Auth\LoginController@login');
    Route::post('register', 'Auth\RegisterController@register');

    Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');
    Route::post('password/reset', 'Auth\ResetPasswordController@reset');

    Route::post('email/verify/{user}', 'Auth\VerificationController@verify')->name('verification.verify');
    Route::post('email/resend', 'Auth\VerificationController@resend');

    Route::post('oauth/{driver}', 'Auth\OAuthController@redirectToProvider');
    Route::get('oauth/{driver}/callback', 'Auth\OAuthController@handleProviderCallback')->name('oauth.callback');
});



Route::group(['middleware' => 'auth:api', 'namespace'=> 'V1', 'prefix'=> 'v1'], function () {

    Route::group(['prefix'=>'dashboard'], function(){
        Route::get('/tracking','DashboardController@tracking');
        Route::get('/ekstraksi','DashboardController@ekstraksi');
        Route::get('/registrasi', 'DashboardController@registrasi');
        Route::get('/pcr','DashboardController@pcr');
        Route::get('/notifications','DashboardController@notifications');
        Route::get('/positif-negatif','DashboardController@positifNegatif');

        Route::get('counter-belum-verifikasi', 'DashboardVerifikasiController@getCountUnverify');
        Route::get('counter-terverifikasi', 'DashboardVerifikasiController@getCountVerified');
        Route::get('counter-belum-validasi', 'DashboardValidasiController@getCountUnvalidate');
        Route::get('counter-tervalidasi', 'DashboardValidasiController@getCountValidated');
    });

    Route::group(['prefix'=>'chart'], function(){
        Route::get('/regis-mandiri','DashboardController@chartMandiri');
        Route::get('/regis-rujukan','DashboardController@chartRujukan');
        Route::get('/positif','DashboardController@chartPositif');
        Route::get('/negatif','DashboardController@chartNegatif');
        Route::get('/ekstraksi','DashboardController@chartEkstraksi');
        Route::get('/pcr','DashboardController@chartPcr');
    });

    Route::group(['prefix'=>'ekstraksi'], function(){
        Route::get('/get-data','EkstraksiController@getData');
        Route::get('/detail/{id}','EkstraksiController@detail');
        Route::post('/edit/{id}','EkstraksiController@edit');
        Route::post('/set-invalid/{id}','EkstraksiController@setInvalid');
        Route::post('/set-proses/{id}','EkstraksiController@setProses');
        Route::post('/terima', 'EkstraksiController@terima');
        Route::post('/kirim', 'EkstraksiController@kirim');
        Route::post('/kirim-ulang', 'EkstraksiController@kirimUlang');
        Route::post('/musnahkan/{id}', 'EkstraksiController@musnahkan');
        Route::post('/set-kurang/{id}', 'EkstraksiController@setKurang');
        Route::post('/set-swab-ulang/{id}', 'EkstraksiController@setSwabUlang');
    });
    Route::group(['prefix'=>'pcr'], function(){
        Route::get('/get-data','PCRController@getData');
        Route::get('/detail/{id}','PCRController@detail');
        Route::post('/edit/{id}','PCRController@edit');
        Route::post('/terima', 'PCRController@terima');
        Route::post('/invalid/{id}','PCRController@invalid');
        Route::post('/input/{id}','PCRController@input');
        Route::post('/upload-grafik','PCRController@uploadGrafik');
        Route::post('/musnahkan/{id}', 'PCRController@musnahkan');
        Route::post('/import-hasil-pemeriksaan','PCRController@importHasilPemeriksaan');
        Route::post('/import-data-hasil-pemeriksaan','PCRController@importDataHasilPemeriksaan');
    });
    Route::group(['prefix'=>'sampel'], function(){
        Route::get('/cek-nomor-sampel','SampelController@cekNomorSampel');
    });

        Route::get('list-kota-jabar', 'KotaController@listKota');
        Route::get('list-kecamatan/{kota}','KotaController@listKecamatan');
        Route::get('list-kelurahan/{kec}','KotaController@listKelurahan');

        Route::get('kota/detail/{kota}', 'KotaController@show');

        Route::get('list-fasyankes-jabar', 'FasyankesController@listByProvinsi');
        Route::get('fasyankes/detail/{fasyankes}', 'FasyankesController@show');

        Route::get('list-gejala', 'GejalaController@getListMasterGejala');
        Route::get('gejala/detail/{gejala}', 'GejalaController@show');
        
        Route::get('list-penyakit-penyerta', 'PenyakitPenyertaController@getListMaster');
        Route::get('penyakit-penyerta/detail/{penyakitPenyerta}', 'PenyakitPenyertaController@show');

        Route::group(['prefix'=>'register'], function(){

            Route::post('WebSatelit','RegisterWebSatelitController@storeWebSatelit');
            Route::post('WebSatelit/update/{regis_id}/{pasien_id}','RegisterWebSatelitController@storeUpdate');
            Route::get('WebSatelit/{register_id}/{pasien_id}','RegisterWebSatelitController@getById');
            Route::delete('WebSatelit/{id}/{pasien}','RegisterWebSatelitController@delete');

            Route::get('/', 'RegisterListController@index');

            Route::post('store', 'RegisterController@store');
            Route::post('mandiri','RegisterController@storeMandiri');
            Route::post('mandiri/update/{regis_id}/{pasien_id}','RegisterController@storeUpdate');
            Route::get('mandiri/{register_id}/{pasien_id}','RegisterController@getById');
            Route::get('delete-sampel/{id}','RegisterController@deleteSample');
            

            Route::get('detail/{register}', 'RegisterController@show');

            Route::post('update/{register}', 'RegisterController@update');

            Route::delete('delete/{register}', 'RegisterController@destroy');

            Route::get('noreg','RegisterController@generateNomorRegister');
            Route::get('get-noreg','RegisterController@requestNomor');

            Route::post('import-mandiri', 'ImportRegisterController@importRegisterMandiri');

            Route::post('import-rujukan', 'ImportRegisterController@importRegisterRujukan');

            
            Route::group(['prefix'=>'rujukan'], function(){

                Route::post('store', 'RegisterRujukanController@store');

                Route::get('detail/{register}', 'RegisterRujukanController@show'); 

                Route::post('update/{register}', 'RegisterRujukanController@update');

                // Route::delete('delete/{register}', 'RegisterRujukanController@destroy');
                Route::delete('delete/{id}/{pasien}','RegistrasiRujukanController@delete');


            });


        });

        Route::group(['prefix'=>'pengambilan-sampel'], function(){

            Route::get('list-dikirim', 'PengambilanListController@listDikirim');

            Route::get('list-sampel-register', 'PengambilanListController@listSampelRegister');
            
            Route::post('store', 'PengambilanSampelController@store');

            Route::post('update/{pengambilan}', 'PengambilanSampelController@update');

            Route::get('detail/{pengambilan}', 'PengambilanSampelController@show');

            Route::delete('delete/{pengambilan}', 'PengambilanSampelController@destroy');

            Route::delete('delete/sampel/{sampel}', 'PengambilanSampelController@destroySampel');

        });

        Route::group(['prefix'=>'sampel'], function(){

            Route::post('store', 'SampelController@store');

            Route::post('update/{sampel}', 'SampelController@update');

            Route::get('detail/{sampel}', 'SampelController@show');

            Route::get('barcode/{barcode}', 'SampelController@showByBarcode');

            Route::delete('delete/{sampel}', 'SampelController@destroy');


        });
   
        Route::group(['prefix'=>'verifikasi'], function(){

            Route::get('list', 'VerifikasiController@index');

            Route::get('list-verified', 'VerifikasiController@indexVerified');

            Route::get('detail/{sampel}', 'VerifikasiController@show');

            Route::get('get-sampel-status', 'VerifikasiController@sampelStatusList');

            Route::post('edit-status-sampel/{sampel}', 'VerifikasiController@updateToVerified');

            Route::get('export-excel', 'VerifikasiExportController@exportExcel');

            Route::post('verifikasi-single-sampel/{sampel}', 'VerifikasiController@verifiedSingleSampel');

            Route::get('list-kategori', 'VerifikasiController@listKategori');
                        
        });

        Route::group(['prefix'=>'validasi'], function(){

            Route::get('list', 'ValidasiController@index');

            Route::get('list-validated', 'ValidasiController@indexValidated');

            Route::get('detail/{sampel}', 'ValidasiController@show');
            
            // Route::get('get-sampel-status', 'ValidasiController@sampelStatusList');
            
            Route::post('edit-status-sampel/{sampel}', 'ValidasiController@updateToValidate');
            
            Route::get('list-validator', 'ValidasiController@getValidator');
            
            Route::get('export-pdf/{sampel}', 'ValidasiExportController@exportPDF');
            
            Route::post('bulk-validasi', 'ValidasiController@bulkValidate');

            Route::get('export-excel', 'ValidasiExportController@exportExcel');

            Route::post('regenerate-pdf/{sampel}', 'ValidasiController@regeneratePdfHasil');
            
        });

       Route::apiResource('validator', 'ValidatorController');

       Route::group(['prefix'=>'pelacakan-sampel'], function(){

            Route::get('list', 'PelacakanSampelController@index');

            Route::get('detail/{sampel}', 'PelacakanSampelController@show');

       });
});