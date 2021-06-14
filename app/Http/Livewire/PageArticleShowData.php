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



    public function mount()
    {
        $this->is_selected = false;
        $this->search = "";
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

        $max_id = Upload::where('file_type', 'json')
            ->where('category', 'Article')
            ->where('original_record_count', '>', 0)
            ->max('id');


        if ($this->search <> "") {

            for ($x = $max_id; $x >= 1; $x--) {
                $data['articles'] = JsonArticle::latest()
                    ->where('title', 'like', '%' . $this->search . '%')
                    ->where('upload_id', $x)
                    ->paginate(50);

                if (count($data['articles']) > 0) {
                    break;
                }
            }
        } else {

            $data['articles'] = JsonArticle::latest()->where('upload_id', $max_id)->paginate(50);
        }
        return view('livewire.page-article-show-data', $data);
    }
}
