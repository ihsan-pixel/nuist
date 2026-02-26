<?php

namespace App\Exports\Instumen;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use App\Models\User;

class PesertaAllExport implements WithMultipleSheets
{
    protected $materiId;

    public function __construct($materiId = null)
    {
        $this->materiId = $materiId;
    }

    public function sheets(): array
    {
        $sheets = [];
        $evaluators = User::whereIn('role', ['pemateri','fasilitator','admin','tenaga_pendidik'])->get();
        foreach ($evaluators as $ev) {
            $title = substr($ev->name ?? ('User_'.$ev->id), 0, 31);
            $sheets[] = new PesertaSheetWithTitle($ev->id, $this->materiId, $title);
        }
        return $sheets;
    }
}

class PesertaSheetWithTitle extends PesertaSheetExport implements \Maatwebsite\Excel\Concerns\WithTitle
{
    protected $title;

    public function __construct($evaluatorId, $materiId, $title)
    {
        parent::__construct($evaluatorId, $materiId);
        $this->title = $title;
    }

    public function title(): string
    {
        return $this->title;
    }
}
