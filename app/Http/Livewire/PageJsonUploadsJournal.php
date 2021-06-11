<?php

namespace App\Http\Livewire;

use App\Models\Upload;
use Livewire\Component;
use Livewire\WithPagination;

class PageJsonUploadsJournal extends Component
{
    use WithPagination;
    public $search_str;

    public function mount()
    {
        $this->search_str = "";
    }
    public function render()
    {
        $data['uploads'] = Upload::latest()
            ->where('file_type', 'json')
            ->where('category', 'Journal')
            ->where('show', true)
            ->where('file_name', 'like', '%' . $this->search_str . '%')
            ->paginate(5);
        return view('livewire.page-json-uploads-journal', $data);
    }
}