<?php

namespace App\Http\Controllers;

use App\Models\Upload;
use App\Models\JsonArticle;
use Illuminate\Http\Request;

class PageJsonArticleController extends Controller
{
    public function index()
    {
        $data['articles'] = Upload::latest()->where('file_type', 'json')->paginate(5);

        return view('PageJsonArticle.index', $data);
    }

    public function show_all_data()
    {
        $data['articles'] = JsonArticle::latest()->paginate(15);

        return view('PageJsonArticle.show_all_data', $data);
    }
}
