<?php

use App\Http\Controllers\Api\TapController;
use Illuminate\Support\Facades\Route;

Route::post('/tap', [TapController::class, 'tap']);
