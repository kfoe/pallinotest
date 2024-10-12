<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\OfferController;

Route::group(['prefix' => 'v1', 'as' => 'v1.'], function () {
    Route::get('offers/{shopIdOrShopCountryCode}', OfferController::class)->name('offers');
});
