<?php

namespace App\Filament\Resources\Bookings\Pages;

use App\Filament\Resources\Bookings\BookingResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Model;

class ListBookings extends ListRecords
{
    protected static string $resource = BookingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->using(function (array $data, string $model) {
                    foreach($data['time'] as $time)
                    {
                        $content = [
                            'date' => $data['date'],
                            'name' => $data['name'],
                            'time' => $time,
                            'email' => $data['email']
                        ];
                        $model::create($content);
                    }

                }),
        ];
    }

}
