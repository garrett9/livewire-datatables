<?php

namespace Mediconesystems\LivewireDatatables;

use Illuminate\Support\Carbon;

class DateColumn extends Column
{
    public const DEFAULT_FORMAT = 'F jS, Y \a\t g:iA';

    public $type = 'date';

    public $callback;

    public function __construct()
    {
        $this->format();
    }

    public function format($format = null)
    {
        $this->callback = function ($value) use ($format) {
            return $value ? Carbon::parse($value)->format($format ?? config('livewire-datatables.default_date_format')) : null;
        };

        return $this;
    }

    public function formatWithTimezone($format = null, $tz = null)
    {
        $this->callback = function ($value) use ($format, $tz) {
            return $value ? Carbon::parse($value)->tz($tz)->format($format ?? self::DEFAULT_FORMAT) : null;
        };

        $this->sortable();

        return $this;
    }
}
