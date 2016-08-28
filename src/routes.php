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

Route::group(['prefix' => Wizzy::getPrefix(), 'namespace' => 'IlGala\LaravelWizzy', 'middleware' => 'web'], function () {
    Route::get('wizzy', ['as' => Wizzy::getPrefix() . '.wizzy', 'uses' => 'WizzyController@index']);
    Route::get('environment', ['as' => Wizzy::getPrefix() . '.environment', 'uses' => 'WizzyController@environment']);
    Route::get('database', ['as' => Wizzy::getPrefix() . '.database', 'uses' => 'WizzyController@database']);
    Route::get('conclusion', ['as' => Wizzy::getPrefix() . '.conclusion', 'uses' => 'WizzyController@conclusion']);
    Route::post('execute', ['as' => Wizzy::getPrefix() . '.execute', 'uses' => 'WizzyController@execute']);
});
