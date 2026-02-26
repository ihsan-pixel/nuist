<?php

namespace App\Exports\Instumen;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use App\Models\TalentaLayananTeknis;

class TeknisAllExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        $sheets = [];
        $layanans = TalentaLayananTeknis::all();
        foreach ($layanans as $l) {
            $sheets[] = new TeknisSheetWithTitle($l->id, $l->nama_layanan_teknis ?? 'Layanan_'.$l->id);
        }
        return $sheets;
    }
}

class TeknisSheetWithTitle extends TeknisSheetExport implements \Maatwebsite\Excel\Concerns\WithTitle
{
    protected $title;

    public function __construct($layananId, $title)
    {
        parent::__construct($layananId);
        $this->title = substr($title, 0, 31);
    }

    public function title(): string
    {
        return $this->title;
    }
}
