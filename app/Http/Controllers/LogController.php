<?php

namespace App\Http\Controllers;

use App\Models\Log;
use Illuminate\Http\Request;

class LogController extends Controller
{
    public function index()
    {
        $data['logs'] = Log::latest()->paginate(50);
        return view('log.index', $data);
    }
}
