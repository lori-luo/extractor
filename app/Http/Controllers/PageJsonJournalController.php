<?php

namespace App\Http\Controllers;

use App\Models\Upload;
use DirectoryIterator;
use App\Models\JsonJournal;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class PageJsonJournalController extends Controller
{
    public function index()
    {

        if (!file_exists(storage_path('app/json/Journal'))) {
            mkdir(storage_path('app/json/Journal'), 0777, true);
        }

        $this->loop_files();
        $this->loop_uploads();

        return view('PageJsonJournal.index');
    }

    public function show_all_data()
    {


        return view('PageJsonJournal.show_all_data');
    }

    public function show_full_article(JsonJournal $journal)
    {
        $data['journal'] = $journal;
        $data['title'] = $data['journal']->title;

        if (isset($data['journal']->publisher_obj()->country)) {

            $data['fav_icon'] = asset('images/flags/png/' . strtolower($data['journal']->publisher_obj()->country) . '.png');
        }

        return view('PageJsonJournal.show_full_article', $data);
    }

    private function loop_files()
    {
        // Shows us all files and directories in directory except "." and "..".
        $files = new DirectoryIterator(storage_path('app/json/Journal'));
        foreach ($files as $fileInfo) {
            if ($fileInfo->isDot() || $fileInfo->isDir()) {
                continue;
            }


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
                $upload->category = 'Journal';
                $upload->show = true;
                $upload->size = $fileInfo->getSize();
                $upload->date_modified = $modified_date;
                $upload->save();

                auth()->user()->logs()->create([
                    'action' => 'Uploaded file: ' . $file_name_only . ".json",
                    'type' => 'upload-file-journal',
                    'obj' => json_encode([
                        'file_name' => $file_name_only
                    ])
                ]);
            } else {

                if ($modified_date > $row->date_modified || !$row->show) {
                    $row->date_modified = date("Y-m-d H:i:s", $fileInfo->getMTime());
                    $row->show = true;
                    $row->size = $fileInfo->getSize();
                    $row->save();

                    auth()->user()->logs()->create([
                        'action' => 'Uploaded file modified: ' . $file_name_only . ".json",
                        'type' => 'modified-file-journal',
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

        $uploads = Upload::where('category', 'Journal')
            ->get();
        foreach ($uploads as $upload) {
            $file_exists = false;
            // Shows us all files and directories in directory except "." and "..".
            $files = new DirectoryIterator(storage_path('app/json/Journal'));
            foreach ($files as $fileInfo) {
                if ($fileInfo->isDot() || $fileInfo->isDir()) {
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
