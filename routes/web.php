<?php

use Illuminate\Support\Facades\Route;

Route::livewire('/', 'welcome-page')->name('welcome');
Route::livewire('/book', 'booking-page');


require __DIR__.'/settings.php';
