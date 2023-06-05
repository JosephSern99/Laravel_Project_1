<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers;
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
Auth::routes();
Route::any("password", function(){
    abort(404);
});
Route::any("password/email", function(){
    abort(404);
});
Route::any("password/confirm", function(){
    abort(404);
});
Route::any("password/reset", function(){
    abort(404);
});
Route::any("register", function(){
    abort(404);
});

//ajax - ssa
Route::post('ssa', [Controllers\SSAController::class, "getSSA"])->name('ajax-ssa');


//javascript
Route::get('js/ssa', [Controllers\JavascriptController::class, "ssa"])->name('javascript.ssa');

Route::group(['middleware' => ['auth']], function () {
    Route::get('', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    Route::resource('fjo', Controllers\FJOHeaderController::class);
	
	Route::get('vtj', [Controllers\ViewTodayJobs::class, 'index'])->name("vtj.index");
	
	Route::get('fjo/{id}/customer', [Controllers\FJOHeaderController::class, 'customer'])->name("fjo.customer");
	
	Route::get('fjo/{id}/task', [Controllers\FJOHeaderController::class, 'task'])->name("fjo.task");
	
	Route::post('fjo/{id}/task/add', [Controllers\FJOHeaderController::class, 'add'])->name("fjo.add");
	
	Route::post('fjo/delete', [Controllers\FJOHeaderController::class, 'delete'])->name("fjo.delete");
	
	Route::post('fjo/{id}/checkin', [Controllers\FJOHeaderController::class, 'checkin'])->name("fjo.checkin");
	
	Route::post('fjo/{id}/checkout', [Controllers\FJOHeaderController::class, 'checkout'])->name("fjo.checkout");
	
	
	Route::post('fjo/{id}/save', [Controllers\FJOHeaderController::class, 'save'])->name("fjo.save");
	
	Route::get('fjo/{id}/signature', [Controllers\FJOHeaderController::class, 'signature'])->name("fjo.signature");
	Route::post('fjo/{id}/finish', [Controllers\FJOHeaderController::class, 'finish'])->name("fjo.finish");
	
	
  
});
