<?php

Route::group([
    'namespace'  => 'Humunuk\Seat\AllianceStructureManagement\Http\Controllers',
    'middleware' => ['web', 'auth', 'locale'],
], function () {

    // Your route definitions go here.
    Route::get('/{alliance}/structures', [
        'as'   => 'alliance-structure-mgmt.index',
        'uses' => 'AllianceStructureController@index'
    ]);

});