<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\JsonJournal;
use Livewire\WithPagination;

class PageJournalShowData extends Component
{

    use WithPagination;

    //  protected $paginationTheme = 'bootstrap';
    public $is_selected;
    public $search_str;
    public $selected_journals = [];
    protected $listeners = [
        'journalDeleted' => '$refresh',
        'journalsDeleted' => '$refresh',
        'selectedJournal' => 'selectedJournalx'
    ];

    public function mount()
    {
        $this->is_selected = false;
        $this->search_str = "";
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




    public function render()
    {

        if ($this->search_str <> "") {

            $data['journals'] = JsonJournal::latest()
                ->where('title', 'like', '%' . $this->search_str . '%')
                ->paginate(10);
        } else {
            $data['journals'] = JsonJournal::latest()->paginate(10);
        }



        return view('livewire.page-journal-show-data', $data);
    }
}
