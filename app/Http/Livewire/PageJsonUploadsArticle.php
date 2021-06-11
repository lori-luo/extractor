<?php

namespace App\Http\Livewire;

use App\Models\Upload;
use Livewire\Component;
use Livewire\WithPagination;


class PageJsonUploadsArticle extends Component
{

    use WithPagination;

    public $search_str;

    public function mount()
    {
        $this->search_str = "";
    }


    public function render()
    {

        $data['articles'] = Upload::orderBy('id', 'desc')
            ->where('file_type', 'json')
            ->where('category', 'Article')
            ->where('file_name', 'like', '%' . $this->search_str . '%')

            ->where('show', true)
            ->paginate(5);


        return view('livewire.page-json-uploads-article', $data);
    }
}
