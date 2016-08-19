<?php

use IlGala\LaravelWizzy\Wizzy;

Route::group(['prefix' => Wizzy::getPrefix(), 'namespace' => 'IlGala\LaravelWizzy', 'middleware' => 'web'], function() {
    Route::get('welcome', ['as' => Wizzy::getPrefix() . '.welcome', 'uses' => 'WizzyController@welcome']);
});
