<?php

use Illuminate\Support\Facades\Route;
use App\Models\Permission;
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

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Keep this guests routes, because we need it to access before login, or you can put it in database but change the type
Auth::routes();

$models = Permission::get();
foreach ($models as $row) {
    $params = $row->params ? '/'.$row->params : '';
    if($row->type=='auth'){
        if($row->name && $row->ctrl_name){
            Route::{$row->method}($row->url.$params, $row->ctrl_path.'@'.$row->ctrl_action)->name($row->name)->middleware($row->type, 'can:'.$row->name);
        }else{
            Route::{$row->method}($row->url.$params, function () use ($row) { return view($row->render_view); })->middleware($row->type, 'can:'.$row->name);
        }
    }else{
        if($row->name && $row->ctrl_name){
            Route::{$row->method}($row->url.$params, $row->ctrl_path.'@'.$row->ctrl_action)->name($row->name)->middleware($row->type);
        }else{
            Route::{$row->method}($row->url.$params, function () use ($row) { return view($row->render_view); })->middleware($row->type);
        }
    }
}