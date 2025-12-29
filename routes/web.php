<?php

use App\Http\Controllers\UrlShortenerController;
use Illuminate\Support\Facades\Route;

Route::get('/{code}', [UrlShortenerController::class, 'redirectToUrl']);
