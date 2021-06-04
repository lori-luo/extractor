<?php

namespace App\Http\Controllers;

use App\Models\Log;
use Illuminate\Http\Request;

class LogController extends Controller
{
    public function index()
    {
        $data['logs'] = Log::latest()->paginate(10);
        return view('log.index', $data);
    }
}
