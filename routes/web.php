<?php

use Illuminate\Support\Facades\Route;

Route::redirect('/', '/schedule');
Route::livewire('/schedule', 'schedule-page')->name('schedule');
Route::livewire('/book', 'booking-page')->name('book');
Route::livewire('/appointment/{uuid}', 'view-appointment')->name('view-appointment');


require __DIR__.'/settings.php';
