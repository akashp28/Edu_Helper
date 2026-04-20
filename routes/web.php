<?php

use App\Http\Controllers\ChatController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ChatController::class, 'index']);
Route::post('/chat', [ChatController::class, 'chat'])->name('chat');
Route::post('/reset', [ChatController::class, 'reset'])->name('chat.reset');
