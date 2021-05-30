<?php

namespace App\Http\Controllers;

use App\Models\Upload;
use App\Models\XmlPubMed;
use Illuminate\Http\Request;

class PageXmlPubMedController extends Controller
{
    public function index()
    {
        $data['xmls'] = Upload::latest()->where('file_type', 'xml')->paginate(5);

        return view('PageXmlPubMed.index', $data);
    }

    public function show_all_data()
    {
        $data['articles'] = XmlPubMed::latest()->paginate(15);
        return view('PageXmlPubMed.show_all_data', $data);
    }

    public function export_data()
    {
        $data['xmls'] = Upload::latest()->where('file_type', 'xml')->get();
        return view('PageXmlPubMed.export_data', $data);
    }
}
