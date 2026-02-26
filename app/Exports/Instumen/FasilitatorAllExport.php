<?php

namespace App\Exports\Instumen;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use App\Models\TalentaFasilitator;

class FasilitatorAllExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        $sheets = [];
        $fasilitators = TalentaFasilitator::all();
        foreach ($fasilitators as $f) {
            $sheets[] = new FasilitatorSheetWithTitle($f->id, $f->nama ?? 'Fasilitator_'.$f->id);
        }
        return $sheets;
    }
}

class FasilitatorSheetWithTitle extends FasilitatorSheetExport implements \Maatwebsite\Excel\Concerns\WithTitle
{
    protected $title;

    public function __construct($fasilitatorId, $title)
    {
        parent::__construct($fasilitatorId);
        $this->title = substr($title, 0, 31);
    }

    public function title(): string
    {
        return $this->title;
    }
}
