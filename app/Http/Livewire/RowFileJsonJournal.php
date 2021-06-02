<?php

namespace App\Http\Livewire;

use Livewire\Component;

class RowFileJsonJournal extends Component
{
    public $journal;
    public $export_qty;
    public $test;

    public function mount()
    {
        $this->test = "test here";
        $this->export_qty = 2;
    }

    public function export()
    {
        $this->test = "export here";
    }
    public function render()
    {
        return view('livewire.row-file-json-journal');
    }
}
