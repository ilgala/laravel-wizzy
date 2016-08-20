<?php

/*
 * This file is part of Laravel Wizzy package.
 *
 * (c) Filippo Galante <filippo.galante@b-ground.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use IlGala\LaravelWizzy\Wizzy;

Route::group(['prefix' => Wizzy::getPrefix(), 'namespace' => 'IlGala\LaravelWizzy', 'middleware' => 'web'], function() {
    Route::get('welcome', ['as' => Wizzy::getPrefix() . '.welcome', 'uses' => 'WizzyController@welcome']);
    Route::get('environment', ['as' => Wizzy::getPrefix() . '.environment', 'uses' => 'WizzyController@environment']);
    Route::get('database', ['as' => Wizzy::getPrefix() . '.database', 'uses' => 'WizzyController@database']);
    Route::post('store', ['as' => Wizzy::getPrefix() . '.store', 'uses' => 'WizzyController@storeSettings']);
});
