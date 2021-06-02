<?php

namespace App\Http\Livewire;

use Livewire\Component;

class RowFileJsonJournal extends Component
{
    public $journal;
    public $test;
    public $export_qty;

    public function mount()
    {
        $this->export_qty = 2;
        $this->test = "test here";
    }

    public function export()
    {
        $this->test = "export there haha";
    }
    public function render()
    {
        return view('livewire.row-file-json-journal');
    }
}
