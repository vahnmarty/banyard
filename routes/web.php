<?php

use Illuminate\Support\Facades\Route;
use Spatie\Browsershot\Browsershot;

Route::redirect('/', '/schedule');
Route::livewire('/schedule', 'schedule-page')->name('schedule');
Route::livewire('/book', 'booking-page')->name('book');
Route::livewire('/appointment/{uuid}', 'view-appointment')->name('view-appointment');

Route::get('/schedule/og-image', function () {
    $image = Browsershot::url(url('/schedule'))
        ->windowSize(1200, 630)
        ->waitUntilNetworkIdle()
        ->screenshot();

    return response($image, 200, [
        'Content-Type' => 'image/png',
    ]);
});


require __DIR__.'/settings.php';
