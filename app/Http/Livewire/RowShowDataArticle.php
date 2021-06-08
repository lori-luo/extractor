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

    public function remove_keyword($keyword)
    {

        $new_keywords = [];
        foreach ($this->article->keyword_obj() as $k) {
            if (!($keyword == $k)) {
                array_push($new_keywords, $k);
            }
        }

        $new_keywords_obj = json_encode($new_keywords);

        $this->article->keywords = $new_keywords_obj;
        $this->article->save();

        auth()->user()->logs()->create([
            'action' => 'Removed Article Keyword : ' . $keyword
        ]);
    }

    public function remove_subject($subject)
    {
        $new_subjects = [];
        foreach ($this->article->subject_obj() as $s) {
            if (!($subject == $s->term)) {

                $new_subject['code'] = $s->code;
                $new_subject['scheme'] = $s->scheme;
                $new_subject['term'] = $s->term;
                array_push($new_subjects, $new_subject);
            }
        }

        $new_subjects_obj = json_encode($new_subjects);

        $this->article->subject = $new_subjects_obj;
        $this->article->save();

        auth()->user()->logs()->create([
            'action' => 'Removed Article Subject : ' . $subject
        ]);
    }

    public function reset_subject()
    {
        $this->article->subject = $this->article->subject_orig;
        $this->article->save();
    }

    public function reset_keywords()
    {
        $this->article->keywords = $this->article->keywords_orig;
        $this->article->save();
    }


    public function render()
    {
        return view('livewire.row-show-data-article');
    }
}
