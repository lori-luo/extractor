<?php

namespace App\Http\Controllers;

use App\Models\Upload;
use DirectoryIterator;
use App\Models\JsonArticle;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;

class PageJsonArticleController extends Controller
{
    public function index()
    {



        $this->loop_files();
        $this->loop_uploads();

        $data['articles'] = Upload::latest()->where('file_type', 'json')->paginate(5);

        return view('PageJsonArticle.index', $data);
    }

    public function show_all_data()
    {
        $data['articles'] = JsonArticle::latest()->paginate(15);

        return view('PageJsonArticle.show_all_data', $data);
    }



    private function loop_files()
    {
        // Shows us all files and directories in directory except "." and "..".
        $files = new DirectoryIterator(storage_path('app/json/Article'));
        foreach ($files as $fileInfo) {
            if ($fileInfo->isDot()) {
                continue;
            }


            $file_name_only = pathinfo($fileInfo->getFilename(), PATHINFO_FILENAME);

            $rows = Upload::where('file_name', $file_name_only)
                ->get();

            if (!$rows->count()) { //not exists
                $upload = new Upload();
                $upload->file_name = $file_name_only;
                $upload->file_type = 'json';
                $new_file_name = $file_name_only . "_" . uniqid() . "_" . Str::random(40) . ".json";
                $upload->new_file_name = $new_file_name;
                $upload->category = 'Article';
                $upload->save();
            }
        }
    }

    private function loop_uploads()
    {

        $uploads = Upload::where('category', 'Article')
            ->get();
        foreach ($uploads as $upload) {
            $file_exists = false;
            // Shows us all files and directories in directory except "." and "..".
            $files = new DirectoryIterator(storage_path('app/json/Article'));
            foreach ($files as $fileInfo) {
                if ($fileInfo->isDot()) {
                    continue;
                }


                $file_name = $fileInfo->getFilename(); //ext included

                if (
                    $file_name == ($upload->file_name . "." . $upload->file_type)
                    ||
                    $file_name == $upload->new_file_name
                ) {
                    $file_exists = true;
                    break;
                }
            }

            if (!$file_exists) {
                $upload->delete();
            }
        }
    }
}
