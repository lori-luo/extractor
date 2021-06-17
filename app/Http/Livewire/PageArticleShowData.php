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
    //  protected $paginationTheme = 'bootstrap';
    protected $listeners = [
        'articleDeleted' => '$refresh',
        'articlesDeleted' => '$refresh',
        'selectedArticle' => 'selectedArticlex'
    ];

    public $to_delete_article;
    public $selected_articles = [];
    public $is_selected;
    public $search;
    public $selected_file;




    public function mount()
    {
        $this->is_selected = false;
        $this->search = "";

        $this->selected_file = (Upload::where('category', 'Article')
            ->orderBy('date_modified', 'desc')->first())->id;
        // $this->articles =  JsonArticle::latest()->simplePaginate(50);

    }

    public function re_search()
    {
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function selectedArticlex(JsonArticle $article, $is_selected)
    {
        if ($is_selected) {
            array_push($this->selected_articles, $article->id);
        } else {
            $selecteds = $this->selected_articles;
            foreach ($selecteds as $key => $art) {
                if ($article->id == $art) {
                    unset($this->selected_articles[$key]);
                }
            }
        }
    }

    public function setSelectAll($isChecked)
    {
        $this->is_selected = $isChecked;

        $this->emit('articlesSelectAll');
    }

    public function delete_selected()
    {
        foreach ($this->selected_articles as $key => $art) {
            $article = JsonArticle::find($art);

            auth()->user()->logs()->create([
                'action' => 'Deleted Article: ' . $article->title,
                'type' => 'delete-article',
                'obj' => json_encode($article)
            ]);



            $article->delete();
        }

        $this->selected_articles = [];
        $this->emit('articlesDeleted');
    }


    public function render()
    {

        if ($this->selected_file == 0) {
            $data['articles'] = JsonArticle::latest()
                ->where(function ($query) {
                    $query->where('title_short', 'like', '%' . $this->search . '%')
                        ->orWhere('title', 'like', '%' . $this->search . '%');
                })
                ->paginate(50);
        } else {
            $data['articles'] = JsonArticle::latest()
                ->where(function ($query) {
                    $query->where('title_short', 'like', '%' . $this->search . '%')
                        ->orWhere('title', 'like', '%' . $this->search . '%');
                })
                ->where('upload_id', $this->selected_file)
                ->paginate(50);
        }




        $data['option_files'] = Upload::where('category', 'Article')
            ->orderBy('date_modified', 'desc')->get();



        return view('livewire.page-article-show-data', $data);
    }
}
