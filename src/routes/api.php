<?php

Route::namespace('LaravelEnso\Members\app\Http\Controllers')
    ->middleware(['web', 'auth', 'core'])
    ->prefix('api/administration')
    ->as('administration.')
    ->group(function () {
        Route::prefix('member')->as('member.')
        ->group(function () {
            Route::get('options', 'MemberSelectController@options')
                ->name('options');
        });

        Route::resource('members', 'MemberController', ['only' => ['index', 'store', 'destroy']]);
    });
