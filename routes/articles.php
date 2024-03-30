<?php
/*
 * Copyright (c) WINGS, 2023.
 * Property of WINGS. The distribution of proprietary software created by WINGS is strictly prohibited.
 * There Will Be Consequences. Think about it.
 */

use App\Http\Controllers\BalanceController;
use App\Http\Controllers\Jetstream\UserProfileController;
use App\Http\Controllers\PlansController;
use App\Http\Controllers\PromocodesController;
use App\Http\Controllers\ServersController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Articles Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// articles main page
Route::get('/', function () {
    return Inertia::render('Articles/Show');
})->name('articles');

Route::get('/terms-of-service', function () {
    return Inertia::render("Articles/TermsOfService");
})->name('articles.terms-of-service');

Route::get('/privacy-policy', function () {
    return Inertia::render("Articles/PrivacyPolicy");
})->name('articles.privacy-policy');


// Minecraft
Route::group(['prefix' => 'minecraft'], function () {
    // Java Edition
    Route::group(['prefix' => 'java'], function () {
        // install plugins
        Route::get('/install/plugins', function () {
            return Inertia::render('Articles/Minecraft/Java/InstallPlugins');
        })->name('articles.minecraft.java.install.plugins');

        // install mods
        Route::get('/install/mods', function () {
            return Inertia::render('Articles/Minecraft/Java/InstallMods');
        })->name('articles.minecraft.java.install.mods');

        // username error fix
        Route::get('/username-error-fix', function () {
            return Inertia::render('Articles/Minecraft/Java/UsernameErrorFix');
        })->name('articles.minecraft.java.username-error-fix');

        // setup proxy
        Route::get('/setup-proxy', function () {
            return Inertia::render('Articles/Minecraft/Java/SetupProxy');
        })->name('articles.minecraft.java.setup-proxy');
    })->name('articles.minecraft.java');
})->name('articles.minecraft');
