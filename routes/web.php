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

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/notes', function(){
    return view('app.notes');
})->name('notes')->middleware('auth');

Route::get('/search', function(){
    return view('app.search');
})->name('search')->middleware('auth');

Route::get('notes/export-excel', 'NoteController@exportExcel')->name('notes.exportExcel')->middleware('auth');
Route::get('notes/export-pdf', 'NoteController@exportPDF')->name('notes.exportPDF')->middleware('auth');