<?php

use Illuminate\Support\Facades\Route;

Route::redirect('/', '/schedule');
Route::livewire('/schedule', 'schedule-page');
Route::livewire('/book', 'booking-page');
Route::livewire('/appointment/{uuid}', 'view-appointment')->name('view-appointment');


require __DIR__.'/settings.php';
