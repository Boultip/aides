<?php

namespace App\Exports;

use App\Perimeter;

class PerimetersExport extends GlobalExport implements GlobalExportInterface
{
    protected $filename = 'perimeters';

    public function collection()
    {
        return Perimeter::get(array_keys($this->columns))->sortBy('name');
    }

}