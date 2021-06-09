<?php

namespace App\Http\Controllers;

use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class LogController extends Controller
{
    public function index()
    {

        return view('log.index');
    }

    public function dl_article_exported($file_name)
    {
        return Response::download(public_path('exports/' . $file_name));
    }
}
