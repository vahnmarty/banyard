<?php

namespace App\Filament\Forms\Components;

use Carbon\Carbon;
use Filament\Forms\Components\Field;

class CalendarPicker extends Field
{
    protected string $view = 'filament.forms.components.calendar-picker';

    protected ?int $month = null;
    protected ?int $year = null;
    public $min_date = null;
    public $max_date = null;

    public function month(?int $month): static
    {
        $this->month = $month;
        return $this;
    }

    public function year(?int $year): static
    {
        $this->year = $year;
        return $this;
    }

    public function minDate($date): static
    {
        $this->min_date = $date;
        return $this;
    }

    public function maxDate($date): static
    {
        $this->max_date = $date;
        return $this;
    }

    public function getMinDate()
    {
        return $this->min_date ?? date('Y-m-d');
    }

    public function getMaxDate()
    {
        return $this->max_date ?? Carbon::now()->addDays(30)->format('Y-m-d');
    }

    public function getMonth(): int
    {
        return $this->month ?? now()->month;
    }

    public function getYear(): int
    {
        return $this->year ?? now()->year;
    }

    public function getMonthName(): string
    {
        return date("F Y", mktime(0, 0, 0, $this->getMonth(), 1, $this->getYear()));
    }



    /**
     * Generate full year calendar structure
     */
    public function getAllDays(): array
    {
        $year = $this->getYear();

        $months = [];

        for ($month = 1; $month <= 12; $month++) {
            $months[] = [
                'month' => $month,
                'month_name' => date("F Y", mktime(0, 0, 0, $month, 1, $year)),
                'days' => $this->getDaysFromMonth($month, $year),
            ];
        }

        return $months;
    }

    /**
     * Generate days with Sunday-first alignment
     */
    public function getDaysFromMonth(int $month, int $year): array
    {
        $days = [];

        $firstDayOfWeek = date('w', strtotime("$year-$month-01")); // 0 = Sunday
        $totalDays = cal_days_in_month(CAL_GREGORIAN, $month, $year);

        // leading nulls
        for ($i = 0; $i < $firstDayOfWeek; $i++) {
            $days[] = null;
        }

        // actual days
        for ($day = 1; $day <= $totalDays; $day++) {
            $days[] = $day;
        }

        // pad to full weeks
        while (count($days) % 7 !== 0) {
            $days[] = null;
        }

        return $days;
    }
}
