<?php

use Illuminate\Support\Facades\Route;
use Modules\AI\Http\Controllers\AIController;

Route::middleware(['web', 'auth'])->prefix('ai')->group(function () {
    Route::get('/daily-insight', [AIController::class, 'getDailyInsight'])->name('ai.daily-insight');
});
