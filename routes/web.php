<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::middleware(['auth:sanctum', 'verified'])->get('/xml-PubMed', function () {
    return view('xml_pub_med');
})->name('xml_pub_med');

Route::middleware(['auth:sanctum', 'verified'])->get('/json-Article', function () {
    return view('json_article');
})->name('json_article');

Route::middleware(['auth:sanctum', 'verified'])->get('/json-Journal', function () {
    return view('json_journal');
})->name('json_journal');
