<?php

namespace App\Http\Livewire;

use Livewire\Component;

class RowShowDataJournal extends Component
{

    public $journal;

    public function reset_subject()
    {
        $this->journal->subject = $this->journal->subject_orig;
        $this->journal->save();
    }

    public function reset_keywords()
    {
        $this->journal->keywords = $this->journal->keywords_orig;
        $this->journal->save();
    }


    public function remove_subject($subject)
    {
        $new_subjects = [];
        foreach ($this->journal->subject_obj() as $s) {
            if (!($subject == $s->term)) {

                $new_subject['code'] = $s->code;
                $new_subject['scheme'] = $s->scheme;
                $new_subject['term'] = $s->term;
                array_push($new_subjects, $new_subject);
            }
        }

        $new_subjects_obj = json_encode($new_subjects);

        $this->journal->subject = $new_subjects_obj;
        $this->journal->save();
    }


    public function remove_keyword($keyword)
    {

        $new_keywords = [];
        foreach ($this->journal->keyword_obj() as $k) {
            if (!($keyword == $k)) {
                array_push($new_keywords, $k);
            }
        }

        $new_keywords_obj = json_encode($new_keywords);

        $this->journal->keywords = $new_keywords_obj;
        $this->journal->save();
    }

    public function render()
    {
        return view('livewire.row-show-data-journal');
    }
}
