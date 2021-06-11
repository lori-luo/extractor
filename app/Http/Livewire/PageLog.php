<?php

namespace App\Http\Livewire;

use App\Models\Log;
use Livewire\Component;
use Livewire\WithPagination;

class PageLog extends Component
{

    use WithPagination;

    public $search_str;

    public function mount()
    {
        $this->search_str = "";
    }

    public function re_search()
    {
    }

    public function render()
    {
        $data['logs'] = Log::latest()
            ->where('obj', 'like', '%' . $this->search_str . '%')
            ->paginate(10);
        return view('livewire.page-log', $data);
    }
}
