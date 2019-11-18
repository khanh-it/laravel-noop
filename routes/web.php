<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// auth
Auth::routes();

//
Route::group([
    'middleware' => ['auth'],
    'prefix' => 'cms',
    // 'namespace' => '',
    // 'name' => '',
    // 'domain' => '',
], function() {
    /** User */
    $prefix = 'user';
    Route::group([
        'prefix' => $prefix,
    ], function() use ($prefix) {
        $prefix = "{$prefix}::";
        $clt = 'UserController@';
        // Define routes
        /* Route::any($act = 'index', ['as' => "{$prefix}{$act}", 'uses' => "{$clt}{$act}"]);
        Route::get($act = 'create', ['as' => "{$prefix}{$act}", 'uses' => "{$clt}create"]);
        Route::post($act = 'store', ['as' => "{$prefix}{$act}", 'uses' => "{$clt}{$act}"]);
        Route::get(($act = 'edit') . '/{id}', ['as' => "{$prefix}{$act}", 'uses' => "{$clt}{$act}"]);
        Route::post(($act = 'update') . '/{id}', ['as' => "{$prefix}{$act}", 'uses' => "{$clt}{$act}"]);
        Route::get(($act = 'delete') . '/{id}', ['as' => "{$prefix}{$act}", 'uses' => "{$clt}{$act}"]);
        Route::post(($act = 'destroy') . '/{id}', ['as' => "{$prefix}{$act}", 'uses' => "{$clt}{$act}"]);
        Route::get(($act = 'profile') . '/{id}', ['as' => "{$prefix}{$act}", 'uses' => "{$clt}{$act}"]); */
        // change password
        Route::get(($act = 'change-password'), ['as' => "{$prefix}{$act}", 'uses' => "{$clt}changePassword"]);
        Route::post(($act = 'change-password/do'), ['as' => "{$prefix}change-password:do", 'uses' => "{$clt}doChangePassword"]);
    });
    /** .end#User */

    /** Dashboard */
    Route::get('', 'HomeController@index')->name('home');
    /** .end#Dashboard */

    /** Tag */
    $prefix = 'tag';
    Route::group([
        'prefix' => $prefix
    ], function() use ($prefix) {
        $prefix = "{$prefix}::";
        $clt = 'TagController@';
        // Define routes
        Route::any($act = 'index', ['as' => "{$prefix}{$act}", 'uses' => "{$clt}{$act}"]);
        Route::get($act = 'create', ['as' => "{$prefix}{$act}", 'uses' => "{$clt}create"]);
        Route::post($act = 'store', ['as' => "{$prefix}{$act}", 'uses' => "{$clt}{$act}"]);
        Route::get(($act = 'edit') . '/{id}', ['as' => "{$prefix}{$act}", 'uses' => "{$clt}{$act}"]);
        Route::post(($act = 'update') . '/{id}', ['as' => "{$prefix}{$act}", 'uses' => "{$clt}{$act}"]);
        Route::get(($act = 'delete') . '/{id}', ['as' => "{$prefix}{$act}", 'uses' => "{$clt}{$act}"]);
        Route::post(($act = 'destroy') . '/{id}', ['as' => "{$prefix}{$act}", 'uses' => "{$clt}{$act}"]);
    });
    /** .end#Tag */

    /** Ads */
    $prefix = 'ads';
    Route::group([
        'prefix' => $prefix
    ], function() use ($prefix) {
        $prefix = "{$prefix}::";
        $clt = 'AdsController@';
        // Define routes
        Route::any($act = 'index', ['as' => "{$prefix}{$act}", 'uses' => "{$clt}{$act}"]);
        Route::get($act = 'create', ['as' => "{$prefix}{$act}", 'uses' => "{$clt}create"]);
        Route::post($act = 'store', ['as' => "{$prefix}{$act}", 'uses' => "{$clt}{$act}"]);
        Route::get(($act = 'edit') . '/{id}', ['as' => "{$prefix}{$act}", 'uses' => "{$clt}{$act}"]);
        Route::post(($act = 'update') . '/{id}', ['as' => "{$prefix}{$act}", 'uses' => "{$clt}{$act}"]);
        Route::match(['get', 'post'], ($act = 'show') . '/{id}', ['as' => "{$prefix}{$act}", 'uses' => "{$clt}{$act}"]);
        Route::get(($act = 'delete') . '/{id}', ['as' => "{$prefix}{$act}", 'uses' => "{$clt}{$act}"]);
        Route::post(($act = 'destroy') . '/{id}', ['as' => "{$prefix}{$act}", 'uses' => "{$clt}{$act}"]);
        Route::get(($act = 'report') . '/{id?}', ['as' => "{$prefix}{$act}", 'uses' => "{$clt}{$act}"]);
    });
    /** .end#Ads */
});

// Resources...
Route::group([
    // 'middleware' => ['auth'],
    'prefix' => 'resources',
    // 'namespace' => '',
    // 'name' => '',
    // 'domain' => '',
], function() {
    /** Html */
    $prefix = 'html';
    Route::group([
        'prefix' => $prefix,
    ], function() {
        $prefix = "{$prefix}::";
        $clt = 'ResourcesController@';
        Route::get($act = 'ads_frame.html', ['as' => "Resources::Html::AdsFrame", 'uses' => "{$clt}htmlAdsFrameAction"]);
    });
    /** .end#Html */

    /** Javascript */
    $prefix = 'js';
    Route::group([
        'prefix' => $prefix,
    ], function() {
        $prefix = "{$prefix}::";
        $clt = 'ResourcesController@';
        Route::get($act = 'widget-ads.js', ['as' => "Resources::Js::WidgetAds", 'uses' => "{$clt}jsWidgetAdsAction"]);
    });
    /** .end#Javascript */
});
