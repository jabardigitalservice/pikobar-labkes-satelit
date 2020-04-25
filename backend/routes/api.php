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
    });

    Route::get('/pengguna', 'PenggunaController@listPengguna');
    Route::post('/pengguna', 'PenggunaController@savePengguna');
    Route::post('/pengguna/{id}', 'PenggunaController@updatePengguna');
    Route::delete('/pengguna/{id}','PenggunaController@deletePengguna');
    Route::get('/pengguna/{id}','PenggunaController@showUpdate');
    Route::get('roles-option','OptionController@getRoles');

    Route::group(['prefix'=>'registrasi-mandiri'], function(){
        Route::get('/','RegistrasiMandiri@getData');
    });

    Route::group(['prefix'=>'ekstraksi'], function(){
        Route::get('/get-data','EkstraksiController@getData');
        Route::get('/get-kirim','EkstraksiController@getKirim');
        Route::get('/get-dikembalikan','EkstraksiController@getDikembalikan');
    });
});

Route::group(['middleware' => 'guest:api'], function () {
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

        Route::get('list-kota-jabar', 'KotaController@listKota');
        Route::get('kota/detail/{kota}', 'KotaController@show');

        Route::get('list-fasyankes-jabar', 'FasyankesController@listByProvinsi');
        Route::get('fasyankes/detail/{fasyankes}', 'FasyankesController@show');

        Route::get('list-gejala', 'GejalaController@getListMasterGejala');
        Route::get('gejala/detail/{gejala}', 'GejalaController@show');
        
        Route::get('list-penyakit-penyerta', 'PenyakitPenyertaController@getListMaster');
        Route::get('penyakit-penyerta/detail/{penyakitPenyerta}', 'PenyakitPenyertaController@show');

        Route::group(['prefix'=>'register'], function(){

            Route::get('/', 'RegisterListController@index');

            Route::get('generate-nomor-register', 'RegisterController@generateNomorRegister');

            Route::post('store', 'RegisterController@store');

            Route::get('detail/{register}', 'RegisterController@show');

            Route::post('update/{register}', 'RegisterController@update');

            Route::delete('delete/{register}', 'RegisterController@destroy');

            
            Route::group(['prefix'=>'rujukan'], function(){

                Route::post('store', 'RegisterRujukanController@store');

                Route::get('detail/{register}', 'RegisterRujukanController@show');

                Route::post('update/{register}', 'RegisterRujukanController@update');

                Route::delete('delete/{register}', 'RegisterRujukanController@destroy');


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
        
});