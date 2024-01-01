<?php

use App\Http\Controllers\ListingController;
use App\Http\Controllers\UserController;
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

// Protected Routes

Route::group(['middleware' => 'auth'], function () {

    // Show Create Form
    Route::get('/listings/create', [ListingController::class, 'create']);

    // Update a Listing
    Route::put('/listings/{listing}', [ListingController::class, 'update']);

    // Delete a Listing
    Route::delete('/listings/{listing}', [ListingController::class, 'destroy']);

    // Store Listing Data
    Route::post('/listings', [ListingController::class, 'store']);

    // Show Edit Form
    Route::get('/listings/{listing}/edit', [ListingController::class, 'edit']);

    // Log User Out
    Route::post('/logout', [UserController::class, 'logout']);

    // Manage Listings
    Route::get('/listings/manage', [ListingController::class, 'manage']);

});

Route::group(['middleware' => 'guest'], function () {

    // Show Register Create Form
    Route::get('/register', [UserController::class, 'create']);

    // Register User
    Route::post('/users', [UserController::class, 'store']);

    // Show Login Form
    Route::get('/login', [UserController::class, 'login'])->name('login');

    // Login
    Route::post('/users/authenticate', [UserController::class, 'authenticate']);

});

// Public Routes

// All Listings
Route::get('/', [ListingController::class, 'index']);

// Single Listing
Route::get('/listings/{listing}', [ListingController::class, 'show']);


