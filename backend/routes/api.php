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

        Route::get('list-fasyankes-jabar', 'FasyankesController@listByProvinsi');

        Route::get('list-gejala', 'GejalaController@getListMasterGejala');
        
        Route::get('list-penyakit-penyerta', 'PenyakitPenyertaController@getListMaster');
        
});