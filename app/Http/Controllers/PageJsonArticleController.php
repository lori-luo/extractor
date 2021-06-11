<?php

namespace App\Http\Controllers;

use App\Models\Upload;
use DirectoryIterator;
use App\Models\JsonArticle;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;

class PageJsonArticleController extends Controller
{

    public function __construct()
    {
        ini_set('memory_limit', '1024M'); // or you could use 1G


    }
    public function index()
    {

        if (!file_exists(storage_path('app/json/Article'))) {
            mkdir(storage_path('app/json/Article'), 0777, true);
        }



        $this->loop_files();
        $this->loop_uploads();



        return view('PageJsonArticle.index');
    }

    public function show_all_data()
    {


        return view('PageJsonArticle.show_all_data');
    }

    public function show_full_article(JsonArticle $article)
    {
        $data['article'] = $article;
        $data['title'] = $data['article']->title;
        return view('PageJsonArticle.show_full_article', $data);
    }



    private function loop_files()
    {
        // Shows us all files and directories in directory except "." and "..".
        $files = new DirectoryIterator(storage_path('app/json/Article'));
        foreach ($files as $fileInfo) {
            if ($fileInfo->isDot()) {
                continue;
            }
            // dd($fileInfo);

            $file_name_only = pathinfo($fileInfo->getFilename(), PATHINFO_FILENAME);

            $row = Upload::where('file_name', $file_name_only)
                ->first();
            $modified_date = date("Y-m-d H:i:s", $fileInfo->getMTime());
            if (!$row) { //not exists
                $upload = new Upload();
                $upload->file_name = $file_name_only;
                $upload->file_type = 'json';
                $new_file_name = $file_name_only . "_" . uniqid() . "_" . Str::random(40) . ".json";
                $upload->new_file_name = $new_file_name;
                $upload->category = 'Article';
                $upload->show = true;
                $upload->date_modified = $modified_date;
                $upload->save();

                auth()->user()->logs()->create([
                    'action' => 'Uploaded file: ' . $file_name_only . ".json",
                    'type' => 'upload-file-article',
                    'obj' => json_encode([
                        'file_name' => $file_name_only
                    ])
                ]);
            } else {

                if ($modified_date > $row->date_modified || !$row->show) {
                    $row->date_modified = date("Y-m-d H:i:s", $fileInfo->getMTime());
                    $row->show = true;
                    $row->save();

                    auth()->user()->logs()->create([
                        'action' => 'Uploaded file modified: ' . $file_name_only . ".json",
                        'type' => 'modified-file-article',
                        'obj' => json_encode([
                            'file_name' => $file_name_only,
                            'modified_date' => $modified_date
                        ])
                    ]);
                }
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
                $upload->show = false;
                $upload->save();
            }
        }
    }
}
