<?php

namespace App\Http\Livewire;

use App\Models\FileLanguage;
use App\Models\Upload;
use Livewire\Component;
use App\Models\JsonArticle;
use Livewire\WithPagination;
use App\Models\SearchLanguage;

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
    public $search_langs;
    public $is_search_langs_reset;




    public function mount()
    {
        $this->is_selected = false;
        $this->search = "";
        $this->search_langs = SearchLanguage::get();
        $this->selected_file = (Upload::where('category', 'Article')
            ->orderBy('date_modified', 'desc')->first())->id;

        $this->is_search_langs_reset = false;
        // $this->articles =  JsonArticle::latest()->simplePaginate(50);

    }

    public function lang_clicked($id, $val)
    {
        $lang = SearchLanguage::find($id);
        $lang_selected = ($val ? true : false);

        $lang->selected = $lang_selected;
        $lang->save();
        $this->is_search_langs_reset = false;
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

    public function lang_reset()
    {

        $langs =   SearchLanguage::get();
        foreach ($langs as $lang) {
            $lang->selected = false;
            $lang->save();
        }

        $this->search_langs = SearchLanguage::get();
        $this->is_search_langs_reset = true;
    }


    public function render()
    {

        $selected_export_langs = SearchLanguage::where('selected', true)->get();


        if ($this->selected_file == 0) {
            $data['articles'] = JsonArticle::latest();
            $data['articles'] = $data['articles']->where(function ($query) {
                $query->where('title_short', 'like', '%' . $this->search . '%')
                    ->orWhere('title', 'like', '%' . $this->search . '%');
            });

            $data['articles'] = $data['articles']->where(function ($data) use ($selected_export_langs) {
                $ctr = 1;
                foreach ($selected_export_langs as $lang) {
                    if ($ctr == 1) {
                        $data = $data->whereJsonContains('journal_language', $lang->code);
                    } else {
                        $data = $data->orWhereJsonContains('journal_language', $lang->code);
                    }
                    $ctr++;
                }
            });


            $data['articles'] = $data['articles']->paginate(50);
        } else {
            $data['articles'] = JsonArticle::latest();
            $data['articles'] = $data['articles']->where(function ($query) {
                $query->where('title_short', 'like', '%' . $this->search . '%')
                    ->orWhere('title', 'like', '%' . $this->search . '%');
            });

            $data['articles'] = $data['articles']->where(function ($data) use ($selected_export_langs) {
                $ctr = 1;
                foreach ($selected_export_langs as $lang) {
                    if ($ctr == 1) {
                        $data = $data->whereJsonContains('journal_language', $lang->code);
                    } else {
                        $data = $data->orWhereJsonContains('journal_language', $lang->code);
                    }
                    $ctr++;
                }
            });

            $data['articles'] = $data['articles']->where('upload_id', $this->selected_file)
                ->paginate(50);
        }




        $data['option_files'] = Upload::where('category', 'Article')
            ->orderBy('date_modified', 'desc')->get();



        return view('livewire.page-article-show-data', $data);
    }
}
