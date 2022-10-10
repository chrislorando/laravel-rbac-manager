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

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware(['auth'])->group(function () {
    $models = DB::table('permissions')->get();
    foreach ($models as $row) {
        $params = $row->params ? '/'.$row->params : '';
        if($row->name){
            Route::{$row->method}($row->url.$params, $row->ctrl_path.'@'.$row->ctrl_action)->name($row->name)->middleware('can:'.$row->name);
        }
    }
});
