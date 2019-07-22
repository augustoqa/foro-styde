<?php

Route::get('register', [
    'uses' => 'RegisterController@create',
    'as' => 'register'
]);

Route::post('register', [
    'uses' => 'RegisterController@store',
]);

Route::get('register/confirmation', [
    'uses' => 'RegisterController@confirmation',
    'as' => 'register_confirmation'
]);