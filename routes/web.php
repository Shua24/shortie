<?php

use App\Http\Controllers\UrlShortenerController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'url-shortener')->name('url.form');

Route::get('/{code}', [UrlShortenerController::class, 'redirectToUrl']);
Route::get('/health', fn () => response()->json(['status' => 'ok']));
