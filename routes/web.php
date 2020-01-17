<?php
declare(strict_types=1);

Route::get('/', 'MainController@index')
     ->name('index');

Route::get('redirect', 'MainController@redirectFromBigMP');

Route::post('search', 'SearchController@search')
     ->name('search');

Route::get('locale/{locale}', 'LangController@change')
     ->name('locale.set');

Route::get('changeOrder', 'MainController@orderBy')
     ->name('order.change')
     ->middleware('throttle:5,1');

Route::post('changeDarkMode', 'MainController@darkMode')
     ->middleware('throttle:5,1');

Route::post('translationClose', 'MainController@dismissTranslation')
     ->middleware('throttle:5,1');

//Route::get('vote', 'MainController@vote')->name('vote');
Route::redirect('/vote', '/');

Route::get('justmonika', 'MainController@justMonika')
     ->name('justmonika');

Route::get('check_country', 'LangController@checkLocale');

if (app()->isLocal()) {
    Route::get('abort/{number}', function ($error) {
        config(['app.debug' => false]);
        abort($error ?? 404);
    });

    Route::get('steam', 'LocalTestController@steam');
}
