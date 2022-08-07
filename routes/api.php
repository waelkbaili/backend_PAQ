<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PlatController;
use App\Http\Controllers\CommentaireController;
use App\Http\Controllers\CommandeController;
use App\Http\Controllers\ChatController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('v1')->group(function () {
    Route::get('/products/{id}', [ProductController::class,'show']);
    Route::get('/products', [ProductController::class,'index']);
    Route::get('/products/search/{name}', [ProductController::class,'search']);
    Route::get('/users/search/{country}', [AuthController::class,'search']);
    Route::post('/register', [AuthController::class,'register']);
    Route::post('/login', [AuthController::class,'login']);
    Route::get('/plats/search/{user_id}', [PlatController::class,'show']);
    Route::get('/plats/{id}', [PlatController::class,'showPlat']);
    Route::get('/commentaires/search/{plat_id}', [CommentaireController::class,'show']);
    Route::get('/commandes/search/{client_id}', [CommandeController::class,'showHistorique']);
    Route::get('/commandes/searchlivraisons/{cuistot_id}', [CommandeController::class,'showLivaison']);
    Route::get('/users/{id}', [AuthController::class,'show']);
    Route::post('/stat', [CommandeController::class,'state']);
    Route::get('/chats/{user_id}', [ChatController::class,'show']);
});


Route::group(['prefix' => 'v1','middleware'=>['auth:sanctum']],function () {

    Route::post('/products/upload', [ProductController::class,'uploadImage']);
    Route::post('/products', [ProductController::class,'store']);
    Route::put('/products/{id}', [ProductController::class,'update']);
    Route::delete('/products/{id}', [ProductController::class,'destroy']);
    Route::post('/logout', [AuthController::class,'logout']);
    Route::post('/plats', [PlatController::class,'store']);
    Route::delete('/plats/{id}', [PlatController::class,'destroy']);
    Route::put('/plats/{id}', [PlatController::class,'update']);
    Route::post('/commentaires', [CommentaireController::class,'store']);
    Route::put('/plats/platnumber/{id}', [PlatController::class,'updatePlatNumber']);
    Route::post('/commandes', [CommandeController::class,'store']);
    Route::put('/users/images/{id}', [AuthController::class,'updateUserImage']);
    Route::post('/chats', [ChatController::class,'store']);
    Route::post('/rating', [CommandeController::class,'rate']);





});

//Route::middleware('auth:sanctum')->get('/user', function () {
  //  Route::get('/products', [ProductController::class,'index']);
//});
