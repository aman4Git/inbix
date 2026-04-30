<?php

use App\Http\Controllers\EmailController;
use Illuminate\Support\Facades\Route;

Route::post('/emails/respond', [EmailController::class, 'respond'])->name('emails.respond');
