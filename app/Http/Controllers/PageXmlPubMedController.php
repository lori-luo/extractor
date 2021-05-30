<?php

namespace App\Http\Controllers;

use App\Models\Upload;
use Illuminate\Http\Request;

class PageXmlPubMedController extends Controller
{
    public function index()
    {
        $data['xmls'] = Upload::latest()->where('file_type', 'xml')->get();
        return view('PageXmlPubMed.index', $data);
    }

    public function show_all_data()
    {
        $data['xmls'] = Upload::latest()->where('file_type', 'xml')->get();
        return view('PageXmlPubMed.show_all_data', $data);
    }
}
