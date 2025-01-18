<?php

use App\Http\Controllers\Actions\Telegram\WebhookHandler;
use Illuminate\Support\Facades\Route;

Route::post('/webhook', WebhookHandler::class);
