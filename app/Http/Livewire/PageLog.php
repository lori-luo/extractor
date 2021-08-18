<?php

namespace App\Http\Livewire;

use App\Models\Log;
use Livewire\Component;
use Livewire\WithPagination;

class PageLog extends Component
{

    use WithPagination;

    public $search;

    public function mount()
    {
        $this->search = "";
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }




    public function render()
    {
        $data['logs'] = Log::latest()
            ->where('obj', 'like', '%' . $this->search . '%')
            ->paginate(30);
        return view('livewire.page-log', $data);
    }
}
