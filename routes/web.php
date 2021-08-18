<?php

use App\Http\Controllers\LogController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\PageXmlPubMedController;
use App\Http\Controllers\PageJsonArticleController;
use App\Http\Controllers\PageJsonJournalController;

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
})->middleware('guest');

Route::get('/php', function () {
    phpinfo();
});

Route::middleware(['auth:sanctum', 'verified'])
    ->get('/dashboard', [LogController::class, 'index'])->name('dashboard');

Route::middleware(['auth:sanctum', 'verified'])
    ->get('/download_exported_article/{file_name}', [LogController::class, 'dl_article_exported'])->name('log.dl_art_export_file');

/*
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

    */

Route::middleware(['auth:sanctum', 'verified'])
    ->get('/json-Article', [PageJsonArticleController::class, 'index'])
    ->name('json_article');
Route::middleware(['auth:sanctum', 'verified'])
    ->get('/json-Article/records', [PageJsonArticleController::class, 'show_all_data'])
    ->name('json_article.data');
Route::middleware(['auth:sanctum', 'verified'])
    ->get('/json-Article/records/{article}', [PageJsonArticleController::class, 'show_full_article'])
    ->name('json_article.data.row');


/*
Route::middleware(['auth:sanctum', 'verified'])
    ->get('/json-Article/export', [PageJsonArticleController::class, 'export_data'])
    ->name('json_article.export');
    */


Route::middleware(['auth:sanctum', 'verified'])
    ->get('/json-Journal', [PageJsonJournalController::class, 'index'])
    ->name('json_journal');
Route::middleware(['auth:sanctum', 'verified'])
    ->get('/json-Journal/records', [PageJsonJournalController::class, 'show_all_data'])
    ->name('json_journal.data');

Route::middleware(['auth:sanctum', 'verified'])
    ->get('/json-Journal/records/{journal}', [PageJsonJournalController::class, 'show_full_article'])
    ->name('json_journal.data.row');
/* 
Route::middleware(['auth:sanctum', 'verified'])
    ->get('/json-Journal/export', [PageJsonJournalController::class, 'export_data'])
    ->name('json_journal.export');
    */
