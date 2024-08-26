<?php

use App\Http\Controllers\IllnessNotificationPDFController;
use Illuminate\Support\Facades\Route;

Route::get('/illness-notifications/{illnessNotification}/pdf', [IllnessNotificationPDFController::class, 'generatePDF'])
    ->name('illness-notifications.pdf');
