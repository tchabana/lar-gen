<?php
use App\Services\GeminiService;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GeminiController;

Route::get('/', [GeminiController::class, 'showMessages']);
