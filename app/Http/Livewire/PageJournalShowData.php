<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\JsonJournal;
use Livewire\WithPagination;
use App\Models\SearchLanguage;
use App\Http\Traits\UploadTrait;

class PageJournalShowData extends Component
{

    use WithPagination;
    use UploadTrait;

    //  protected $paginationTheme = 'bootstrap';
    public $is_selected;
    public $search;
    public $selected_journals = [];
    protected $listeners = [
        'journalDeleted' => '$refresh',
        'journalsDeleted' => '$refresh',
        'selectedJournal' => 'selectedJournalx'
    ];

    public $search_langs;
    public $search_langs_arr = [];

    public function mount()
    {
        $this->is_selected = false;
        $this->search = "";
        $this->search_langs = SearchLanguage::get();

        $this->search_langs_arr = $this->set_search_langs();
    }

    public function selectedJournalx(JsonJournal $journal, $is_selected)
    {
        if ($is_selected) {
            array_push($this->selected_journals, $journal->id);
        } else {
            $selecteds = $this->selected_journals;
            foreach ($selecteds as $key => $art) {
                if ($journal->id == $art) {
                    unset($this->selected_journals[$key]);
                }
            }
        }
    }

    public function delete_selected()
    {
        foreach ($this->selected_journals as $key => $j) {
            $journal = JsonJournal::find($j);

            auth()->user()->logs()->create([
                'action' => 'Deleted Journal: ' . $journal->title,
                'type' => 'delete-journal',
                'obj' => json_encode($journal)
            ]);


            $journal->delete();
        }

        $this->selected_journals = [];
        $this->emit('journalsDeleted');
    }

    public function re_search()
    {
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function lang_reset_arr($type = 'reset')
    {

        foreach ($this->search_langs_arr as $key => $lang) {
            $this->search_langs_arr[$key]['selected'] = ($type == 'reset'
                ? ($lang['code'] == 'EN' || $lang['code'] == 'ZH' ? true : false)
                : ($type == 'select' ? true : false));
        }
    }

    public function lang_reset($type = 'reset')
    {
        $langs =   SearchLanguage::get();
        foreach ($langs as $lang) {

            $lang->selected = ($type == 'reset'
                ? ($lang->code == 'EN' || $lang->code == 'ZH' ? true : false)
                : ($type == 'select' ? true : false));
            $lang->save();
        }

        $this->search_langs = SearchLanguage::get();
    }

    public function lang_clicked_search_pre_arr($id, $val)
    {
        $this->search_langs_arr[$id]['selected'] = ($val ? true : false);
    }

    public function lang_clicked_search_pre($id, $val)
    {
        $this->search_langs = $this->lang_clicked_search($id, $val);
    }



    public function render()
    {

        $selected_export_langs = SearchLanguage::where('selected', true)->get();

        if ($this->search <> "") {

            $data['journals'] = JsonJournal::latest();

            $data['journals'] = $data['journals']->where(function ($query) {
                $query->where('title_short', 'like', '%' . $this->search . '%')
                    ->orWhere('title', 'like', '%' . $this->search . '%');
            });

            $data['journals'] = $data['journals']->where(function ($data) use ($selected_export_langs) {
                $ctr = 1;
                foreach ($this->search_langs_arr as $key => $lang) {
                    if ($this->search_langs_arr[$key]['selected']) {
                        if ($ctr == 1) {
                            $data = $data->whereJsonContains('language', $lang['code']);
                        } else {
                            $data = $data->orWhereJsonContains('language', $lang['code']);
                        }
                        $ctr++;
                    }
                }
            });

            $data['journals'] = $data['journals']->paginate(50);
        } else {
            $data['journals'] = JsonJournal::latest();
            $data['journals'] = $data['journals']->where(function ($data) use ($selected_export_langs) {
                $ctr = 1;
                foreach ($this->search_langs_arr as $key => $lang) {
                    if ($this->search_langs_arr[$key]['selected']) {
                        if ($ctr == 1) {
                            $data = $data->whereJsonContains('language', $lang['code']);
                        } else {
                            $data = $data->orWhereJsonContains('language', $lang['code']);
                        }
                        $ctr++;
                    }
                }
            });
            $data['journals'] = $data['journals']->paginate(50);
        }



        return view('livewire.page-journal-show-data', $data);
    }
}
