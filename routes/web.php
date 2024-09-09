<?php

use App\Http\Controllers\IllnessNotificationPDFController;
use Illuminate\Support\Facades\Route;

Route::get('/illness-notifications/{illnessNotification}/pdf', [IllnessNotificationPDFController::class, 'generatePDF'])
    ->name('illness-notifications.pdf');
Route::get('/bill/{bill}/pdf', [\App\Http\Controllers\BillPDFController::class, 'generatePDF'])->name('bill.pdf');
//Route::get('/bill/{bill}/pdf', [\App\Http\Controllers\BillPDFController::class, 'viewPDF'])->name('bill.pdf');
