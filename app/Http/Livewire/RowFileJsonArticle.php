<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\JsonArticle;
use Illuminate\Support\Str;
use JsonMachine\JsonMachine;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;



class RowFileJsonArticle extends Component
{

    public $article;
    public $path;
    public $export_file_name;
    public $insert_tag;
    public $export_qty;

    public $export_range_min;
    public $export_range_max;

    public $test;

    public $export_qty_text;


    public function mount()
    {
        $this->export_range_min = 1;
        $this->export_range_max = 100;
        $this->export_qty = 1;
    }
    public function dl_clean_data()
    {


        $data = JsonArticle::where('upload_id', $this->article->id)
            ->whereBetween('ctr', [$this->export_range_min, $this->export_range_max])
            ->get();

        $rows = [];
        $ctr = 1;

        foreach ($data as $d) {
            if ($d->journal_title) {

                $row['bibjson']['journal']['issns'] = json_decode($d->journal_issns);
            }

            if ($d->journal_title) {
                $row['bibjson']['journal']['title'] = $d->journal_title;
            }


            if (!is_null($d->year)) {
                $row['bibjson']['year'] = $d->year;
            }


            if (!is_null($d->month)) {
                $row['bibjson']['month'] = $d->month;
            }


            if ($d->subject) {
                $row['bibjson']['subject'] = json_decode($d->subject);
            }


            if ($d->author_list) {
                $row['bibjson']['author'] = json_decode($d->author_list);
            }


            if ($d->title) {
                $row['bibjson']['title'] =  $d->title;
            }


            if ($d->abstract) {
                $row['bibjson']['abstract'] =  $d->abstract;
            }


            if ($d->identifier_list) {
                $row['bibjson']['identifier'] = json_decode($d->identifier_list);
            }

            if ($d->link_list) {
                $row['bibjson']['link'] = json_decode($d->link_list);
            }

            if ($d->keywords) {
                $row['bibjson']['keywords'] = json_decode($d->keywords);
            }

            if ($d->journal_license) {
                $row['bibjson']['journal']['license'] = json_decode($d->journal_license);
            }





            array_push($rows, $row);
        }



        $data_file = json_encode($rows, JSON_PRETTY_PRINT);
        $fileName = $this->article->file_name . "_CLEAN_" .  time() . '.json';
        $fileName = $this->article->file_name . "_CLEAN_" .  $this->export_qty_text . '.json';

        $fileName2 = $this->article->file_name;
        File::put(public_path('exports\\' . $fileName), $data_file);

        $this->export_file_name = $fileName;
    }

    public function read_json_article()
    {


        $this->path = storage_path('app\json\Article\\') . $this->article->file_name . '.json';
        //$rows = json_decode(file_get_contents($path), true);
        $rows = JsonMachine::fromFile($this->path);



        $limit = 100000;
        $limit_ctr = 0;

        $ctr = 1;

        $this->insert_tag = Str::random(40) . "_" . uniqid();


        $data = [];
        // JsonArticle::where('upload_id',  $this->article->id)->delete();

        foreach ($rows as $row) {

            if ($limit <= $limit_ctr) {
                break;
            }





            $new_row['article_id'] = $row['id'];
            $new_row['upload_id'] = $this->article->id;
            $new_row['insert_tag'] = $this->insert_tag;

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
            if (isset($row['bibjson']['link'])) {
                $new_row['link_list'] = json_encode($row['bibjson']['link']);
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

            if (isset($row['bibjson']['year'])) {
                $new_row['year'] = $row['bibjson']['year'];
            }
            if (isset($row['bibjson']['month'])) {
                $new_row['month'] = $row['bibjson']['month'];
            }

            if (isset($row['bibjson']['start_page'])) {
                $new_row['start_page'] = $row['bibjson']['start_page'];
            }
            if (isset($row['bibjson']['subject'])) {
                $new_row['subject'] = json_encode($row['bibjson']['subject']);
            }



            if (isset($row['bibjson']['keywords'])) {
                $new_row['keywords'] = json_encode($row['bibjson']['keywords']);
            }




            $new_row['last_updated'] = $row['last_updated'];
            $new_row['created_date'] = $row['created_date'];
            $new_row['created_at'] = Carbon::now();
            $new_row['updated_at'] = Carbon::now();
            $new_row['ctr'] = $ctr;


            array_push($data, $new_row);


            if ($ctr++ % 1 === 0) {
                JsonArticle::insertOrIgnore($data);
                // JsonArticle::insert($data); // Eloquent approach
                $data = [];
            }








            $limit_ctr++;
        }
    }

    public function export()
    {

        if ($this->export_qty == 1) {
            $this->export_range_min = 1;
            $this->export_range_max = 20000;

            $this->export_qty_text = "1-20k";
        }

        if ($this->export_qty == 2) {
            $this->export_range_min = 20001;
            $this->export_range_max = 40000;
            $this->export_qty_text = "20-40k";
        }

        if ($this->export_qty == 3) {
            $this->export_range_min = 40001;
            $this->export_range_max = 60000;
            $this->export_qty_text = "40-60k";
        }

        if ($this->export_qty == 4) {
            $this->export_range_min = 60001;
            $this->export_range_max = 80000;
            $this->export_qty_text = "60-80k";
        }

        if ($this->export_qty == 5) {
            $this->export_range_min = 80001;
            $this->export_range_max = 100000;
            $this->export_qty_text = "80-100k";
        }





        $this->dl_clean_data();
        // $this->export_file_name .= "_CLEANx_.json";
        return Response::download(public_path('exports\\' . $this->export_file_name));
    }




    public function render()
    {
        return view('livewire.row-file-json-article');
    }
}
