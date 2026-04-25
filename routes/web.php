<?php

use Illuminate\Support\Facades\Route;

Route::redirect('/', '/schedule');
Route::livewire('/schedule', 'schedule-page');
Route::livewire('/book', 'booking-page');


require __DIR__.'/settings.php';
