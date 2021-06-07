<?php

namespace App\Http\Livewire;

use Livewire\Component;

class RowShowDataArticle extends Component
{
    public $article;

    public $edit;

    public function mount()
    {
        $this->edit = false;
    }

    public function show_edit()
    {
        $this->edit = true;
    }

    public function save_edit()
    {
        $this->edit = false;
    }


    public function render()
    {
        return view('livewire.row-show-data-article');
    }
}
