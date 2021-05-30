<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\PageXmlPubMedController;

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

Route::middleware(['auth:sanctum', 'verified'])
    ->get('/xml-PubMed', [PageXmlPubMedController::class, 'index'])
    ->name('xml_pub_med');
Route::middleware(['auth:sanctum', 'verified'])
    ->get('/xml-PubMed/records', [PageXmlPubMedController::class, 'show_all_data'])
    ->name('xml_pub_med.data');
Route::middleware(['auth:sanctum', 'verified'])
    ->get('/xml-PubMed/export', [PageXmlPubMedController::class, 'export_data'])
    ->name('xml_pub_med.export');


Route::middleware(['auth:sanctum', 'verified'])
    ->post('/xml-PubMed/upload', [UploadController::class, 'store'])
    ->name('xml_pub_med.upload.store');

Route::middleware(['auth:sanctum', 'verified'])->get('/json-Article', function () {
    return view('json_article');
})->name('json_article');

Route::middleware(['auth:sanctum', 'verified'])->get('/json-Journal', function () {
    return view('json_journal');
})->name('json_journal');
