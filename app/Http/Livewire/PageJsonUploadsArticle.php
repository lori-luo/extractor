<?php

namespace App\Http\Livewire;

use App\Models\Upload;
use Livewire\Component;
use Livewire\WithPagination;


class PageJsonUploadsArticle extends Component
{

    use WithPagination;

    public $search;

    public function mount()
    {
        $this->search  = "";
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }


    public function render()
    {



        $data['articles'] = Upload::orderBy('id', 'desc')
            ->where('file_type', 'json')
            ->where('category', 'Article')
            ->where('file_name', 'like', '%' . $this->search . '%')

            ->where('show', true)
            ->paginate(50);


        return view('livewire.page-json-uploads-article', $data);
    }
}
