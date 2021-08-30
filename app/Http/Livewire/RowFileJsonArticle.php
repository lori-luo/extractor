<?php

namespace App\Http\Livewire;

use App\Models\Upload;
use Livewire\Component;
use App\Models\JsonArticle;
use Illuminate\Support\Str;
use App\Models\FileLanguage;
use JsonMachine\JsonMachine;
use Illuminate\Support\Carbon;
use App\Http\Traits\UploadTrait;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;




class RowFileJsonArticle extends Component
{

    use UploadTrait;

    public $article;
    public $path;
    public $export_file_name;
    public $insert_tag;
    public $export_qty;
    public $export_skip;
    public $export_take;

    public $export_range_min;
    public $export_range_max;

    public $test;

    public $export_qty_text;

    public $row_count;
    public $import_force;

    public $export_sel_all;
    public $export_sel_new;
    public $export_sel_updated;

    public $sel_type;

    public $export_qty_category;

    public $to_import_type;
    public $to_import_type_title;
    public $to_import_type_warning;

    public $export_languages;
    public $export_languages_arr;

    public function __construct()
    {
        ini_set('max_execution_time', 600); //10 minutes
    }



    public function mount()
    {
        $this->export_range_min = 1;
        $this->export_range_max = 100;
        $this->export_qty = 1;

        $this->row_count = 0;
        $this->import_force = false;

        $this->export_languages_arr = $this->set_file_export_langs($this->article);

        $this->export_qty_category = 1; //per 10k
        $this->export_qty_category = 2; //per 20k


        $this->sel_type = 2; //2=new,1=all,3=updated

        $this->to_import_type_title = "";
        $this->to_import_type_warning = "";
        $this->export_languages = $this->article->languages;
    }

    public function lang_clicked_pre($id, $val)
    {
        $this->export_languages = $this->lang_clicked($id, $val);
    }

    public function lang_clicked_pre_arr($id, $val)
    {
        $this->export_languages_arr[$id]['selected'] = ($val ? true : false);
    }

    public function lang_reset_arr($type = 'reset')
    {
        foreach ($this->export_languages_arr as $key => $lang) {
            $this->export_languages_arr[$key]['selected'] = ($type == 'reset'
                ? ($lang['code'] == 'EN' || $lang['code'] == 'ZH' ? true : false)
                : ($type == 'select' ? true : false));
        }
    }

    public function lang_reset($type = 'reset')
    {
        $langs =   FileLanguage::where('upload_id', $this->article->id)->get();
        foreach ($langs as $lang) {

            $lang->selected = ($type == 'reset'
                ? ($lang->code == 'EN' || $lang->code == 'ZH' ? true : false)
                : ($type == 'select' ? true : false));
            $lang->save();
        }

        $this->export_languages = FileLanguage::where('upload_id', $this->article->id)->get();
    }





    public function dl_clean_data()
    {
        //  ini_set('memory_limit', '512M');

        $skip = $this->export_skip;
        $take = $this->export_take;


        if ($this->sel_type == 1) {
            $data = JsonArticle::where('upload_id', $this->article->id);

            $data = $data->where(function ($data) {
                $ctr = 1;
                foreach ($this->export_languages_arr as $lang) {
                    if ($lang['selected']) {
                        if ($ctr == 1) {
                            $data = $data->whereJsonContains('journal_language', $lang['code']);
                        } else {
                            $data = $data->orWhereJsonContains('journal_language', $lang['code']);
                        }
                        $ctr++;
                    }
                }
            });

            $data = $data->skip($skip)->take($take);
            $data = $data->get();
        }

        if ($this->sel_type == 2) {
            $data = JsonArticle::where('upload_id', $this->article->id);

            $data = $data->where(function ($data) {
                $ctr = 1;
                foreach ($this->export_languages_arr as $lang) {
                    if ($lang['selected']) {
                        if ($ctr == 1) {
                            $data = $data->whereJsonContains('journal_language', $lang['code']);
                        } else {
                            $data = $data->orWhereJsonContains('journal_language', $lang['code']);
                        }
                        $ctr++;
                    }
                }
            });

            $data = $data->skip($skip)->take($take);
            $data = $data->where('is_new', true);
            $data = $data->orWhere('is_updated', true);
            $data = $data->get();
        }


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

        $export_dir = public_path('exports/');
        if (!File::exists($export_dir)) {
            //  dd('no path');
            File::makeDirectory($export_dir);
            //  dd('created path');
        }


        File::put(public_path('exports/' . $fileName), $data_file);

        $this->export_file_name = $fileName;

        auth()->user()->logs()->create([
            'action' => 'Export file: ' . $this->export_file_name,
            'type' => 'export-article',
            'obj' => json_encode([
                'file_name' => $fileName
            ])
        ]);
    }



    public function set_to_read_json_type($type)
    {
        $this->to_import_type = $type;

        if ($type == "force") {
            $this->import_force = true;
            $this->to_import_type_title = "Force Import";
            $this->to_import_type_warning = "This will delete all old data and will import all data from the file.";
        } elseif ($type == "import") {
            $this->import_force = false;
            $this->to_import_type_title = "Import";
            $this->to_import_type_warning = "This will only import new and updated records.";
        }
    }

    public function import_json()
    {

        $this->path = storage_path('app/json/Article/') . $this->article->file_name . '.json';




        //$rows = json_decode(file_get_contents($path), true);
        $rows = JsonMachine::fromFile($this->path);


        $limit = 200000;
        //   $limit = 1000;
        $limit_ctr = 0;
        $record_ctr = 0;
        $extracted_ctr = 0;
        $record_new_ctr = 0;
        $record_updated_ctr = 0;
        $import_start = Carbon::now();
        $languages = [];
        $lang_count = [];

        JsonArticle::where('upload_id', $this->article->id)->update([
            'is_new' => false,
            'is_updated' => false
        ]);

        if ($this->import_force) {
            JsonArticle::where('upload_id', $this->article->id)->delete();
        }


        $ctr = JsonArticle::where('upload_id', $this->article->id)->max('ctr') + 1;
        $record_ctr_all = JsonArticle::where('upload_id', $this->article->id)->count();

        $this->insert_tag = Str::random(40) . "_" . uniqid();


        $data = [];
        // JsonArticle::where('upload_id',  $this->article->id)->delete();

        foreach ($rows as $row) {

            $record_ctr++;
            $this->row_count = $record_ctr;

            if ($limit <= $record_ctr) { // limit_ctr only includes qualified records
                break;
            }



            if (!$this->is_subject_medical(json_encode($row['bibjson']['subject']))) {
                //if not medical, skip
                continue;
            }




            $article = JsonArticle::where('article_id', $row['id'])->first();
            $article_exists = false;
            if (!$article) {

                $article = new JsonArticle();
                $record_new_ctr++;
            } else {
                //record exists
                $article_exists = true;
                $last_updated = $article->last_updated;
            }

            $article->full_row_obj =  json_encode($row);


            $article->article_id = $row['id'];
            $article->upload_id = $this->article->id;
            $article->insert_tag = $this->insert_tag;

            $article->title_short = $record_new_ctr;
            if (isset($row['bibjson']['title'])) {
                $article->title = $row['bibjson']['title'];
                $article->title_short = Str::substr($article->title, 0, 180) . ' ' . $record_new_ctr;
                $article->slug =  Str::slug($article->title, '-');
            }


            if (isset($row['bibjson']['abstract'])) {
                $article->abstract = $row['bibjson']['abstract'];
            }

            $article->identifier_list = json_encode($row['bibjson']['identifier']);

            if (isset($row['bibjson']['author'])) {
                $article->author_list = json_encode($row['bibjson']['author']);
            }

            if (isset($row['bibjson']['link'])) {
                $article->link_list = json_encode($row['bibjson']['link']);
            }

            if (isset($row['bibjson']['journal']['volume'])) {
                $article->journal_volume = $row['bibjson']['journal']['volume'];
            }
            if (isset($row['bibjson']['journal']['number'])) {
                $article->journal_number = $row['bibjson']['journal']['number'];
            }

            if (isset($row['bibjson']['journal']['country'])) {
                $article->journal_country = $row['bibjson']['journal']['country'];
            }
            if (isset($row['bibjson']['journal']['publisher'])) {
                $article->journal_publisher = $row['bibjson']['journal']['publisher'];
            }

            if (isset($row['bibjson']['journal']['language'])) {
                $article->journal_language = json_encode($row['bibjson']['journal']['language']);
                $langs = json_decode($article->journal_language);
                foreach ($langs as $lang) {
                    if (!in_array($lang, $languages)) {
                        array_push($languages, $lang);
                    }
                    if (isset($lang_count[$lang])) {
                        $lang_count[$lang]++;
                    } else {
                        $lang_count[$lang] = 1;
                    }
                }
            }
            if (isset($row['bibjson']['journal']['title'])) {
                $article->journal_title = $row['bibjson']['journal']['title'];
            }
            if (isset($row['bibjson']['journal']['license'])) {
                $article->journal_license = json_encode($row['bibjson']['journal']['license']);
            }

            if (isset($row['bibjson']['journal']['issns'])) {
                $article->journal_issns = json_encode($row['bibjson']['journal']['issns']);
            }

            if (isset($row['bibjson']['year'])) {
                $article->year = $row['bibjson']['year'];
            }
            if (isset($row['bibjson']['month'])) {
                $article->month = $row['bibjson']['month'];
            }

            if (isset($row['bibjson']['start_page'])) {
                $article->start_page = $row['bibjson']['start_page'];
            }
            if (isset($row['bibjson']['subject'])) {
                $article->subject = json_encode($row['bibjson']['subject']);
                $article->subject_orig = json_encode($row['bibjson']['subject']);
            }

            if (isset($row['bibjson']['keywords'])) {
                $article->keywords = json_encode($row['bibjson']['keywords']);
                $article->keywords_orig = json_encode($row['bibjson']['keywords']);
            }


            $article->last_updated = date('Y-m-d h:i:s', strtotime($row['last_updated']));
            $article->created_date = date('Y-m-d h:i:s', strtotime($row['created_date']));

            if ($article_exists) {
                //compare the last update date
                if (
                    date('Y-m-d h:i:s', strtotime($row['last_updated'])) > $last_updated
                ) {
                    $article->is_updated = true;
                    $article->save();

                    $record_updated_ctr++;
                    $extracted_ctr++;
                }
            } else { //not exists,means new
                //  dd('taro');
                $article->is_new = true;
                $article->ctr = $ctr;
                $article->save();

                $extracted_ctr++;
            }
            $ctr++;
            $limit_ctr++;
        }

        //  $languages = array_values(array_unique($languages));
        // dd($languages);

        $import_end = Carbon::now();
        $extracted_ctr =  JsonArticle::where('upload_id', $this->article->id)->count();
        $this->article->original_record_count = $record_ctr;
        $this->article->extracted_record_count = $extracted_ctr;
        $this->article->new_record_count = $record_new_ctr;
        $this->article->updated_record_count = $record_updated_ctr;
        $this->article->import_start = $import_start;
        $this->article->import_end = $import_end;


        $this->article->save();

        $this->insert_file_languages($this->article, $languages, $lang_count);
        $this->export_languages_arr = $this->set_file_export_langs($this->article);

        $this->export_languages =  Upload::find($this->article->id)->languages;


        auth()->user()->logs()->create([
            'action' => 'Import file: ' . $this->article->file_name . ".json",
            'type' => 'import-article',
            'obj' => json_encode($this->article)
        ]);
    }

    public function export()
    {

        $this->set_export_prop_pre();

        $this->dl_clean_data();
        // $this->export_file_name .= "_CLEANx_.json";



        return Response::download(public_path('exports/' . $this->export_file_name));
    }

    private function set_export_prop_pre()
    {
        $export = $this->set_export_prop($this->export_qty_category, $this->export_qty);
        $this->export_skip = $export['export_skip'];
        $this->export_qty_text = $export['export_qty_text'];
        $this->export_take = $export['export_take'];
    }




    public function render()
    {
        return view('livewire.row-file-json-article');
    }

    public function export_new()
    {

        $this->set_export_prop_pre();

        $this->dl_clean_data_new();
        return Response::download(public_path('exports/' . $this->export_file_name));
    }

    public function dl_clean_data_new()
    {
        //  ini_set('memory_limit', '512M');

        $skip = $this->export_skip;
        $take = $this->export_take;


        if ($this->sel_type == 1) {
            $data = JsonArticle::where('upload_id', $this->article->id);

            $data = $data->where(function ($data) {
                /*$ctr = 1;
                foreach ($this->export_languages_arr as $lang) {
                    if ($lang['selected']) {
                        if ($ctr == 1) {
                            $data = $data->whereJsonContains('journal_language', $lang['code']);
                        } else {
                            $data = $data->orWhereJsonContains('journal_language', $lang['code']);
                        }
                        $ctr++;
                    }
                }*/
                $data = $data->whereJsonContains('journal_language', 'EN');
                $data = $data->orWhereJsonContains('journal_language', 'ZH');
            });

            $data = $data->skip($skip)->take($take);
            $data = $data->get();
        }

        if ($this->sel_type == 2) {
            $data = JsonArticle::where('upload_id', $this->article->id);

            $data = $data->where(function ($data) {
                /*$ctr = 1;
                foreach ($this->export_languages_arr as $lang) {
                    if ($lang['selected']) {
                        if ($ctr == 1) {
                            $data = $data->whereJsonContains('journal_language', $lang['code']);
                        } else {
                            $data = $data->orWhereJsonContains('journal_language', $lang['code']);
                        }
                        $ctr++;
                    }
                }*/
                $data = $data->whereJsonContains('journal_language', 'EN');
                $data = $data->orWhereJsonContains('journal_language', 'ZH');
            });

            $data = $data->skip($skip)->take($take);
            $data = $data->where('is_new', true);
            $data = $data->orWhere('is_updated', true);
            $data = $data->get();
        }


        $rows = [];
        $ctr = 1;
        $detector = new \LanguageDetector\LanguageDetector();

        foreach ($data as $d) {
            if ($d->title) {
                $language = $detector->evaluate($d->title)->getLanguage();
                if (!in_array($language,array('en','zh-cn','zh-tw')))
                {
                    continue;
                }                    
            }

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

        $export_dir = public_path('exports/');
        if (!File::exists($export_dir)) {
            //  dd('no path');
            File::makeDirectory($export_dir);
            //  dd('created path');
        }


        File::put(public_path('exports/' . $fileName), $data_file);

        $this->export_file_name = $fileName;

        auth()->user()->logs()->create([
            'action' => 'Export file: ' . $this->export_file_name,
            'type' => 'export-article-new',
            'obj' => json_encode([
                'file_name' => $fileName
            ])
        ]);
    }

    public function export_csv()
    {
        $this->set_export_prop_pre();
        $this->dl_clean_data_csv();
        // $this->export_file_name .= "_CLEANx_.json";

        return Response::download(public_path('exports/' . $this->export_file_name));
    } 
    
    public function dl_clean_data_csv()
    {
        //  ini_set('memory_limit', '512M');

        $skip = $this->export_skip;
        $take = $this->export_take;


        if ($this->sel_type == 1) {
            $data = JsonArticle::where('upload_id', $this->article->id);

            $data = $data->where(function ($data) {
                $ctr = 1;
                foreach ($this->export_languages_arr as $lang) {
                    if ($lang['selected']) {
                        if ($ctr == 1) {
                            $data = $data->whereJsonContains('journal_language', $lang['code']);
                        } else {
                            $data = $data->orWhereJsonContains('journal_language', $lang['code']);
                        }
                        $ctr++;
                    }
                }
            });

            $data = $data->skip($skip)->take($take);
            $data = $data->get();
        }

        if ($this->sel_type == 2) {
            $data = JsonArticle::where('upload_id', $this->article->id);

            $data = $data->where(function ($data) {
                $ctr = 1;
                foreach ($this->export_languages_arr as $lang) {
                    if ($lang['selected']) {
                        if ($ctr == 1) {
                            $data = $data->whereJsonContains('journal_language', $lang['code']);
                        } else {
                            $data = $data->orWhereJsonContains('journal_language', $lang['code']);
                        }
                        $ctr++;
                    }
                }
            });

            $data = $data->skip($skip)->take($take);
            $data = $data->where('is_new', true);
            $data = $data->orWhere('is_updated', true);
            $data = $data->get();
        }


        $rows = [];
        $ctr = 1;
        $data_csv = [];
        $data_csv[] = [
            'journal_title','journal_issns','year','month','subject','author_list','title','abstract','identifier_list','link_list','keywords','journal_license'
        ];

        foreach ($data as $d) {
            $data_csv[] = array(
                $d->journal_title,
                $d->journal_issns,
                $d->year,
                $d->month,
                $d->subject,
                $d->author_list,
                $d->title,
                $d->abstract,
                $d->identifier_list,
                $d->link_list,
                $d->keywords,
                $d->journal_license,
            );
            
        }
        $fileName = $this->article->file_name . "_CLEAN_" .  time() . '.csv';
        $fileName = $this->article->file_name . "_CLEAN_" .  $this->export_qty_text . '.csv';

        $fileName2 = $this->article->file_name;

        $export_dir = public_path('exports/');
        if (!File::exists($export_dir)) {
            //  dd('no path');
            File::makeDirectory($export_dir);
            //  dd('created path');
        }

        $fp = fopen(public_path('exports/' . $fileName), 'w');
        foreach ($data_csv as $fields) {
            fputcsv($fp, $fields);
        }
        fclose($fp);
        
        $this->export_file_name = $fileName;

        auth()->user()->logs()->create([
            'action' => 'Export file: ' . $this->export_file_name,
            'type' => 'export-article-csv',
            'obj' => json_encode([
                'file_name' => $fileName
            ])
        ]);
    }

    public function export_new_csv()
    {
        $this->set_export_prop_pre();
        $this->dl_clean_data_new_csv();
        // $this->export_file_name .= "_CLEANx_.json";

        return Response::download(public_path('exports/' . $this->export_file_name));
    } 

    public function dl_clean_data_new_csv()
    {
        $skip = $this->export_skip;
        $take = $this->export_take;


        if ($this->sel_type == 1) {
            $data = JsonArticle::where('upload_id', $this->article->id);

            $data = $data->where(function ($data) {
                $ctr = 1;
                /*foreach ($this->export_languages_arr as $lang) {
                    if ($lang['selected']) {
                        if ($ctr == 1) {
                            $data = $data->whereJsonContains('journal_language', $lang['code']);
                        } else {
                            $data = $data->orWhereJsonContains('journal_language', $lang['code']);
                        }
                        $ctr++;
                    }
                }*/

                $data = $data->whereJsonContains('journal_language', 'EN');
                $data = $data->orWhereJsonContains('journal_language', 'ZH');
            });

            $data = $data->skip($skip)->take($take);
            $data = $data->get();
        }

        if ($this->sel_type == 2) {
            $data = JsonArticle::where('upload_id', $this->article->id);

            $data = $data->where(function ($data) {
                $ctr = 1;
                /*foreach ($this->export_languages_arr as $lang) {
                    if ($lang['selected']) {
                        if ($ctr == 1) {
                            $data = $data->whereJsonContains('journal_language', $lang['code']);
                        } else {
                            $data = $data->orWhereJsonContains('journal_language', $lang['code']);
                        }
                        $ctr++;
                    }
                }*/

                $data = $data->whereJsonContains('journal_language', 'EN');
                $data = $data->orWhereJsonContains('journal_language', 'ZH');
            });

            $data = $data->skip($skip)->take($take);
            $data = $data->where('is_new', true);
            $data = $data->orWhere('is_updated', true);
            $data = $data->get();
        }


        $rows = [];
        $ctr = 1;
        $data_csv = [];
        $data_csv[] = [
            'journal_title','journal_issns','year','month','subject','author_list','title','abstract','identifier_list','link_list','keywords','journal_license'
        ];
        $detector = new \LanguageDetector\LanguageDetector();

        foreach ($data as $d) {
            if ($d->title) {
                $language = $detector->evaluate($d->title)->getLanguage();
                if (!in_array($language,array('en','zh-cn','zh-tw')))
                {
                    continue;
                }                    
            }
            
            $data_csv[] = array(
                $d->journal_title,
                $d->journal_issns,
                $d->year,
                $d->month,
                $d->subject,
                $d->author_list,
                $d->title,
                $d->abstract,
                $d->identifier_list,
                $d->link_list,
                $d->keywords,
                $d->journal_license,
            );
            
        }
        $fileName = $this->article->file_name . "_CLEAN_" .  time() . '.csv';
        $fileName = $this->article->file_name . "_CLEAN_" .  $this->export_qty_text . '.csv';

        $fileName2 = $this->article->file_name;

        $export_dir = public_path('exports/');
        if (!File::exists($export_dir)) {
            //  dd('no path');
            File::makeDirectory($export_dir);
            //  dd('created path');
        }

        $fp = fopen(public_path('exports/' . $fileName), 'w');
        foreach ($data_csv as $fields) {
            fputcsv($fp, $fields);
        }
        fclose($fp);
        
        $this->export_file_name = $fileName;

        auth()->user()->logs()->create([
            'action' => 'Export file: ' . $this->export_file_name,
            'type' => 'export-article-csv',
            'obj' => json_encode([
                'file_name' => $fileName
            ])
        ]);
    }
}
