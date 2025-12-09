<?php

use Illuminate\Support\Facades\Route;
use Modules\AI\Http\Controllers\AIController;

Route::middleware(['web', 'auth'])->prefix('ai')->group(function () {
    Route::get('/daily-insight', [AIController::class, 'getDailyInsight'])->name('ai.daily-insight');
    Route::get('/test-models', [AIController::class, 'testModels'])->name('ai.test-models');
});
