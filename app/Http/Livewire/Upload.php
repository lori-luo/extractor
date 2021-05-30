<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Uploas as X;

class Upload extends Component
{

    public $filename;

    public function fileUpload()
    {
        $validatedData = $this->validate([
            'filename' => 'required'
        ]);

        Upload::insert($validatedData);
        // $filename=$this->filename->store('files','public');
    }

    public function render()
    {
        return view('livewire.upload');
    }
}
