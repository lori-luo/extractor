<?php

namespace App\Http\Livewire;

use App\Models\Log;
use Livewire\Component;
use Livewire\WithPagination;

class PageLog extends Component
{

    use WithPagination;

    public function render()
    {
        $data['logs'] = Log::latest()->simplePaginate(10);
        return view('livewire.page-log', $data);
    }
}
