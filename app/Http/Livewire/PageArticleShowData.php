<?php

namespace App\Http\Livewire;

use App\Models\Upload;
use Livewire\Component;
use App\Models\JsonArticle;
use Livewire\WithPagination;

class PageArticleShowData extends Component
{
    //  public $articles;

    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public function mount()
    {
        // $this->articles =  JsonArticle::latest()->simplePaginate(50);
    }


    public function render()
    {
        $max_id = Upload::where('file_type', 'json')
            ->where('category', 'Article')
            ->where('original_record_count', '>', 0)
            ->max('id');
        $data['articles'] = JsonArticle::latest()->where('upload_id', $max_id)->simplePaginate(20);


        return view('livewire.page-article-show-data', $data);
    }
}
