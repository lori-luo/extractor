<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\JsonArticle;
use JsonMachine\JsonMachine;
use Illuminate\Support\Carbon;

class RowFileJsonArticle extends Component
{

    public $article;
    public $path;

    public function read_json_article()
    {

        $this->path = storage_path('app') . "\\" . $this->article->file_type . "\\" . $this->article->new_file_name;
        //$rows = json_decode(file_get_contents($path), true);
        $rows = JsonMachine::fromFile($this->path);

        $ctr = 1;

        $limit = 10000;
        $limit_ctr = 1;

        $data = [];


        $i = 0;
        foreach ($rows as $row) {
            //  dd($row);
            if ($limit == $limit_ctr) {
                break;
            }
            $new_row['article_id'] = $row['id'];

            if (isset($row['bibjson']['title'])) {
                $new_row['title'] = $row['bibjson']['title'];
            }
            if (isset($row['bibjson']['abstract'])) {
                $new_row['abstract'] = $row['bibjson']['abstract'];
            }

            $new_row['identifier_list'] = json_encode($row['bibjson']['identifier']);

            if (isset($row['bibjson']['author'])) {
                $new_row['author_list'] = json_encode($row['bibjson']['author']);
            }

            if (isset($row['bibjson']['journal']['volume'])) {
                $new_row['journal_volume'] = $row['bibjson']['journal']['volume'];
            }
            if (isset($row['bibjson']['journal']['number'])) {
                $new_row['journal_number'] = $row['bibjson']['journal']['number'];
            }
            if (isset($row['bibjson']['journal']['country'])) {
                $new_row['journal_country'] = $row['bibjson']['journal']['country'];
            }
            if (isset($row['bibjson']['journal']['publisher'])) {
                $new_row['journal_publisher'] = $row['bibjson']['journal']['publisher'];
            }
            if (isset($row['bibjson']['journal']['language'])) {
                $new_row['journal_language'] = json_encode($row['bibjson']['journal']['language']);
            }
            if (isset($row['bibjson']['journal']['title'])) {
                $new_row['journal_title'] = $row['bibjson']['journal']['title'];
            }
            if (isset($row['bibjson']['journal']['license'])) {
                $new_row['journal_license'] = json_encode($row['bibjson']['journal']['license']);
            }
            if (isset($row['bibjson']['journal']['issns'])) {
                $new_row['journal_issns'] = json_encode($row['bibjson']['journal']['issns']);
            }



            $new_row['last_updated'] = $row['last_updated'];
            $new_row['created_date'] = $row['created_date'];
            $new_row['created_at'] = Carbon::now();
            $new_row['updated_at'] = Carbon::now();
            // $new_row['json_id'] = $row['id'];
            //$new_row['json_created_date'] = $row['created_date'];



            // $data_jbibs = [];

            // $new_jbib_row['title'] = $row['bibjson']['title'];



            array_push($data, $new_row);
            /*
            $journal = new Journal();

            $journal->last_updated = $row['last_updated'];
            $journal->save();
            echo $ctr . '.' . $row['last_updated'] . '<br>';
           
            */

            if ($ctr % 100 == 0) {

                JsonArticle::insert($data); // Eloquent approach
                $data = [];
            }
            $ctr++;
            $limit_ctr++;
        }
    }

    public function render()
    {
        return view('livewire.row-file-json-article');
    }
}
