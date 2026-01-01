<?php

use App\Http\Controllers\UrlShortenerController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::controller(UrlShortenerController::class)->group(function () {
    Route::post('generate-url', 'generateUrl');
});
