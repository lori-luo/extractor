<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\JsonJournal;
use Livewire\WithPagination;

class PageJournalShowData extends Component
{

    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    protected $listeners = [
        'journalDeleted' => '$refresh'
    ];



    public function render()
    {
        $data['journals'] = JsonJournal::latest()->simplePaginate(20);

        return view('livewire.page-journal-show-data', $data);
    }
}
