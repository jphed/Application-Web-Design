<?php

use App\Http\Controllers\TicketController;
use Illuminate\Support\Facades\Route;

Route::apiResource('tickets', TicketController::class);

Route::patch('tickets/{ticket}/status', [TicketController::class, 'updateStatus']);
