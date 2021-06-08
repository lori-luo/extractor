<?php

namespace App\Http\Livewire;

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
        return view('livewire.page-article-show-data', [
            'articles' =>  JsonArticle::latest()
                ->where('upload_id', 52)
                ->simplePaginate(20)
        ]);
    }
}
