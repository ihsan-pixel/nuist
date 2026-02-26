<?php

namespace App\Exports\Instumen;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use App\Models\TalentaPemateri;

class PemateriAllExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        $sheets = [];
        $pemateris = TalentaPemateri::all();
        foreach ($pemateris as $p) {
            $sheets[] = new PemateriSheetWithTitle($p->id, $p->nama ?? 'Pemateri_'.$p->id);
        }
        return $sheets;
    }
}

class PemateriSheetWithTitle extends PemateriSheetExport implements \Maatwebsite\Excel\Concerns\WithTitle
{
    protected $title;

    public function __construct($pemateriId, $title)
    {
        parent::__construct($pemateriId);
        $this->title = substr($title, 0, 31);
    }

    public function title(): string
    {
        return $this->title;
    }
}
