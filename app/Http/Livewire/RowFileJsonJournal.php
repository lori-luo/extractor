<?php

namespace App\Http\Livewire;

use App\Models\Upload;


use Livewire\Component;
use App\Models\JsonJournal;
use Illuminate\Support\Str;
use App\Models\FileLanguage;
use JsonMachine\JsonMachine;
use Illuminate\Support\Carbon;
use App\Http\Traits\UploadTrait;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

class RowFileJsonJournal extends Component
{
    use UploadTrait;

    public $journal;
    public $export_qty;
    public $export_skip;
    public $export_take;
    public $export_qty_text;
    public $test;
    public $sel_type;
    public $import_force;
    public $row_count;

    public $to_import_type;
    public $to_import_type_title;
    public $to_import_type_warning;


    public $export_qty_category;
    public $export_languages;
    public $export_languages_arr;

    public function mount()
    {

        $this->export_qty = 1;
        $this->sel_type = 2;
        $this->row_count = 0;

        $this->export_qty_category = 1; //per 10k
        $this->export_qty_category = 2; //per 20k

        $this->to_import_type_title = "";
        $this->to_import_type_warning = "";
        $this->export_languages = $this->journal->languages;
        $this->export_languages_arr = $this->set_file_export_langs($this->journal);
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

    public function export()
    {

        $this->set_export_prop_pre();

        $this->dl_clean_data();
        return Response::download(public_path('exports/' . $this->export_file_name));
    }

    private function set_export_prop_pre()
    {
        $export = $this->set_export_prop($this->export_qty_category, $this->export_qty);
        $this->export_skip = $export['export_skip'];
        $this->export_qty_text = $export['export_qty_text'];
        $this->export_take = $export['export_take'];
    }


    public function lang_reset_arr($type = 'reset')
    {
        foreach ($this->export_languages_arr as $key => $lang) {
            $this->export_languages_arr[$key]['selected'] = ($type == 'reset'
                ? ($lang['code'] == 'EN' || $lang['code'] == 'ZH' ? true : false)
                : ($type == 'select' ? true : false));
        }
    }

    public function lang_clicked_pre_arr($id, $val)
    {
        $this->export_languages_arr[$id]['selected'] = ($val ? true : false);
    }

    public function lang_clicked_pre($id, $val)
    {

        $this->export_languages = $this->lang_clicked($id, $val);
    }

    public function lang_reset($type = 'reset')
    {
        $langs =   FileLanguage::where('upload_id', $this->journal->id)->get();
        foreach ($langs as $lang) {

            $lang->selected = ($type == 'reset'
                ? ($lang->code == 'EN' || $lang->code == 'ZH' ? true : false)
                : ($type == 'select' ? true : false));
            $lang->save();
        }

        $this->export_languages = FileLanguage::where('upload_id', $this->journal->id)->get();
    }



    private function dl_clean_data()
    {
        $skip = $this->export_skip;
        $take = $this->export_take;




        if ($this->sel_type == 1) {
            $data = JsonJournal::where('upload_id', $this->journal->id);

            $data = $data->where(function ($data) {
                $ctr = 1;
                foreach ($this->export_languages_arr as $lang) {
                    if ($lang['selected']) {
                        if ($ctr == 1) {
                            $data = $data->whereJsonContains('language', $lang['code']);
                        } else {
                            $data = $data->orWhereJsonContains('language', $lang['code']);
                        }
                        $ctr++;
                    }
                }
            });


            $data = $data->skip($skip)->take($take);
            $data = $data->get();
        }

        if ($this->sel_type == 2) {
            $data = JsonJournal::where('upload_id', $this->journal->id);

            $data = $data->where(function ($data) {
                $ctr = 1;
                foreach ($this->export_languages_arr as $lang) {
                    if ($lang['selected']) {
                        if ($ctr == 1) {
                            $data = $data->whereJsonContains('language', $lang['code']);
                        } else {
                            $data = $data->orWhereJsonContains('language', $lang['code']);
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

            if ($d->keywords) {
                $row['bibjson']['keywords'] = json_decode($d->keywords);
            }

            if ($d->subject) {
                $row['bibjson']['subject'] = json_decode($d->subject);
            }
            if ($d->eissn) {
                $row['bibjson']['eissn'] = ($d->eissn);
            }
            if ($d->title) {
                $row['bibjson']['title'] = ($d->title);
            }
            if ($d->ref) {

                $row['bibjson']['ref']['aims_scope'] = json_decode($d->ref)->aims_scope;
            }
            if ($d->language) {
                $row['bibjson']['language'] = json_decode($d->language);
            }

            if ($d->publisher) {
                $row['bibjson']['publisher']['country'] = json_decode($d->publisher)->country;
                $row['bibjson']['publisher']['name'] = json_decode($d->publisher)->name;
            }

            if (!is_null($d->institution)) {
                if (isset(json_decode($d->institution)->name)) {
                    $row['bibjson']['institution']['name'] = json_decode($d->institution)->name;
                }
            }

            array_push($rows, $row);
        }

        $data_file = json_encode($rows, JSON_PRETTY_PRINT);
        $fileName = $this->journal->file_name . "_CLEAN_" .  time() . '.json';
        $fileName = $this->journal->file_name . "_CLEAN_" .  $this->export_qty_text . '.json';

        $fileName2 = $this->journal->file_name;
        File::put(public_path('exports/' . $fileName), $data_file);

        $this->export_file_name = $fileName;

        auth()->user()->logs()->create([
            'action' => 'Export file: ' . $this->export_file_name,
            'type' => 'export-journal',
            'obj' => json_encode([
                'file_name' => $fileName
            ])
        ]);
    }

    public function import_json()
    {
        $this->path = storage_path('app/json/Journal/') . $this->journal->file_name . '.json';
        //$rows = json_decode(file_get_contents($path), true);
        $rows = JsonMachine::fromFile($this->path);

        $limit = 200000;
        //   $limit = 100;
        $limit_ctr = 0;
        $record_ctr = 0;
        $extracted_ctr = 0;
        $record_new_ctr = 0;
        $record_updated_ctr = 0;
        $languages = [];
        $lang_count = [];

        $import_start = Carbon::now();

        //   $test = "";

        JsonJournal::where('upload_id', $this->journal->id)->update([
            'is_new' => false,
            'is_updated' => false
        ]);

        if ($this->import_force) {
            JsonJournal::where('upload_id', $this->journal->id)->delete();
        }

        $ctr = JsonJournal::where('upload_id', $this->journal->id)->max('ctr') + 1;
        $record_ctr_all = JsonJournal::where('upload_id', $this->journal->id)->count();

        foreach ($rows as $row) {

            $record_ctr++;
            $this->row_count = $record_ctr;

            if ($limit <= $record_ctr) { // limit_ctr only includes qualified records
                break;
            }


            //  $test = $test . " | " . $record_ctr;
            if (isset($row['bibjson']['subject'])) {
                if (!$this->is_subject_medical(json_encode($row['bibjson']['subject']))) {
                    //if not medical, skip
                    continue;
                }
            }


            $journal = JsonJournal::where('journal_id', $row['id'])->first();
            $journal_exists = false;

            if (!$journal) {

                $journal = new JsonJournal();
                $record_new_ctr++;
            } else {
                //record exists
                $journal_exists = true;
                $last_updated = $journal->last_updated;
            }

            $journal->full_row_obj =  json_encode($row);

            $journal->journal_id = $row['id'];
            $journal->upload_id = $this->journal->id;

            if (isset($row['bibjson']['editorial'])) {
                $journal->editorial = json_encode($row['bibjson']['editorial']);
            }
            if (isset($row['bibjson']['pid_scheme'])) {
                $journal->pid_scheme = json_encode($row['bibjson']['pid_scheme']);
            }
            if (isset($row['bibjson']['copyright'])) {
                $journal->copyright = json_encode($row['bibjson']['copyright']);
            }

            if (isset($row['bibjson']['keywords'])) {
                $journal->keywords = json_encode($row['bibjson']['keywords']);
                $journal->keywords_orig = json_encode($row['bibjson']['keywords']);
            }

            if (isset($row['bibjson']['plagiarism'])) {
                $journal->plagiarism = json_encode($row['bibjson']['plagiarism']);
            }

            if (isset($row['bibjson']['subject'])) {
                $journal->subject = json_encode($row['bibjson']['subject']);
                $journal->subject_orig = json_encode($row['bibjson']['subject']);
            }

            if (isset($row['bibjson']['eissn'])) {
                $journal->eissn =  $row['bibjson']['eissn'];
            }

            if (isset($row['bibjson']['pissn'])) {
                $journal->pissn =  $row['bibjson']['pissn'];
            }

            if (isset($row['bibjson']['language'])) {
                $journal->language =  json_encode($row['bibjson']['language']);
                $langs = json_decode($journal->language);
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


            $journal->title_short = $record_new_ctr;
            if (isset($row['bibjson']['title'])) {
                $journal->title = $row['bibjson']['title'];
                $journal->title_short = Str::substr($journal->title, 0, 180) . ' ' . $record_new_ctr;
                $journal->slug =  Str::slug($journal->title, '-');
            }

            if (isset($row['bibjson']['article'])) {
                $journal->article = json_encode($row['bibjson']['article']);
            }
            if (isset($row['bibjson']['institution'])) {
                $journal->institution = json_encode($row['bibjson']['institution']);
            }

            if (isset($row['bibjson']['preservation'])) {
                $journal->preservation = json_encode($row['bibjson']['preservation']);
            }

            if (isset($row['bibjson']['license'])) {
                $journal->license = json_encode($row['bibjson']['license']);
            }

            if (isset($row['bibjson']['ref'])) {
                $journal->ref = json_encode($row['bibjson']['ref']);
            }

            if (isset($row['bibjson']['apc'])) {
                $journal->apc = json_encode($row['bibjson']['apc']);
            }

            if (isset($row['bibjson']['other_charges'])) {
                $journal->other_charges = json_encode($row['bibjson']['other_charges']);
            }

            if (isset($row['bibjson']['publication_time_weeks'])) {
                $journal->publication_time_weeks = ($row['bibjson']['publication_time_weeks']);
            }

            if (isset($row['bibjson']['deposit_policy'])) {
                $journal->deposit_policy = json_encode($row['bibjson']['deposit_policy']);
            }

            if (isset($row['bibjson']['publisher'])) {
                $journal->publisher = json_encode($row['bibjson']['publisher']);
            }

            if (isset($row['bibjson']['boai'])) {
                $journal->boai = ($row['bibjson']['boai']);
            }
            if (isset($row['bibjson']['waiver'])) {
                $journal->waiver = json_encode($row['bibjson']['waiver']);
            }
            if (isset($row['admin'])) {
                $journal->admin = json_encode($row['admin']);
            }








            $journal->last_updated = date('Y-m-d h:i:s', strtotime($row['last_updated']));
            $journal->created_date = date('Y-m-d h:i:s', strtotime($row['created_date']));


            if ($journal_exists) {
                //compare the last update date
                if (
                    date('Y-m-d h:i:s', strtotime($row['last_updated'])) > $last_updated
                ) {
                    $journal->is_updated = true;
                    $journal->save();

                    $record_updated_ctr++;
                    $extracted_ctr++;
                }
            } else { //not exists,means new
                $journal->is_new = true;
                $journal->ctr = $ctr;
                $journal->save();

                $extracted_ctr++;
            }



            $ctr++;
            $limit_ctr++;
        }

        // dd($test);
        $import_end = Carbon::now();
        $extracted_ctr =  JsonJournal::where('upload_id', $this->journal->id)->count();
        $this->journal->original_record_count = $record_ctr;
        $this->journal->extracted_record_count = $extracted_ctr;
        $this->journal->new_record_count = $record_new_ctr;
        $this->journal->updated_record_count = $record_updated_ctr;
        $this->journal->import_start = $import_start;
        $this->journal->import_end = $import_end;


        $this->journal->save();
        $this->insert_file_languages($this->journal, $languages, $lang_count);
        $this->export_languages_arr = $this->set_file_export_langs($this->journal);
        $this->export_languages =  Upload::find($this->journal->id)->languages;


        auth()->user()->logs()->create([
            'action' => 'Import file: ' . $this->journal->file_name . ".json",
            'type' => 'import-journal',
            'obj' => json_encode($this->journal)
        ]);
    }




    public function render()
    {
        return view('livewire.row-file-json-journal');
    }

    public function export_new()
    {

        $this->set_export_prop_pre();

        $this->dl_clean_data_new();
        return Response::download(public_path('exports/' . $this->export_file_name));
    }

    private function dl_clean_data_new()
    {
        $skip = $this->export_skip;
        $take = $this->export_take;

        if ($this->sel_type == 1) {
            $data = JsonJournal::where('upload_id', $this->journal->id);

            $data = $data->where(function ($data) {
                /*$ctr = 1;
                foreach ($this->export_languages_arr as $lang) {
                    if ($lang['selected']) {
                        if ($ctr == 1) {
                            $data = $data->whereJsonContains('language', $lang['code']);
                        } else {
                            $data = $data->orWhereJsonContains('language', $lang['code']);
                        }
                        $ctr++;
                    }
                }*/
                $data = $data->whereJsonContains('language', 'EN');
                $data = $data->orWhereJsonContains('language', 'ZH');
            });


            $data = $data->skip($skip)->take($take);
            $data = $data->get();
        }

        if ($this->sel_type == 2) {
            $data = JsonJournal::where('upload_id', $this->journal->id);

            $data = $data->where(function ($data) {
                /*$ctr = 1;
                foreach ($this->export_languages_arr as $lang) {
                    if ($lang['selected']) {
                        if ($ctr == 1) {
                            $data = $data->whereJsonContains('language', $lang['code']);
                        } else {
                            $data = $data->orWhereJsonContains('language', $lang['code']);
                        }
                        $ctr++;
                    }
                }*/
                $data = $data->whereJsonContains('language', 'EN');
                $data = $data->orWhereJsonContains('language', 'ZH');
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

            if ($d->keywords) {
                $row['bibjson']['keywords'] = json_decode($d->keywords);
            }

            if ($d->subject) {
                $row['bibjson']['subject'] = json_decode($d->subject);
            }
            if ($d->eissn) {
                $row['bibjson']['eissn'] = ($d->eissn);
            }
            if ($d->title) {
                $row['bibjson']['title'] = ($d->title);
            }
            if ($d->ref) {

                $row['bibjson']['ref']['aims_scope'] = json_decode($d->ref)->aims_scope;
            }
            if ($d->language) {
                $row['bibjson']['language'] = json_decode($d->language);
            }

            if ($d->publisher) {
                $row['bibjson']['publisher']['country'] = json_decode($d->publisher)->country;
                $row['bibjson']['publisher']['name'] = json_decode($d->publisher)->name;
            }

            if (!is_null($d->institution)) {
                if (isset(json_decode($d->institution)->name)) {
                    $row['bibjson']['institution']['name'] = json_decode($d->institution)->name;
                }
            }

            array_push($rows, $row);
        }

        $data_file = json_encode($rows, JSON_PRETTY_PRINT);
        $fileName = $this->journal->file_name . "_CLEAN_" .  time() . '.json';
        $fileName = $this->journal->file_name . "_CLEAN_" .  $this->export_qty_text . '.json';

        $fileName2 = $this->journal->file_name;
        File::put(public_path('exports/' . $fileName), $data_file);

        $this->export_file_name = $fileName;

        auth()->user()->logs()->create([
            'action' => 'Export file: ' . $this->export_file_name,
            'type' => 'export-journal-new',
            'obj' => json_encode([
                'file_name' => $fileName
            ])
        ]);
    }

    public function export_csv()
    {
        $this->set_export_prop_pre();

        $this->dl_clean_data_csv();
        return Response::download(public_path('exports/' . $this->export_file_name));        
    }

    public function dl_clean_data_csv()
    {
        $skip = $this->export_skip;
        $take = $this->export_take;

        if ($this->sel_type == 1) {
            $data = JsonJournal::where('upload_id', $this->journal->id);

            $data = $data->where(function ($data) {
                $ctr = 1;
                foreach ($this->export_languages_arr as $lang) {
                    if ($lang['selected']) {
                        if ($ctr == 1) {
                            $data = $data->whereJsonContains('language', $lang['code']);
                        } else {
                            $data = $data->orWhereJsonContains('language', $lang['code']);
                        }
                        $ctr++;
                    }
                }
            });


            $data = $data->skip($skip)->take($take);
            $data = $data->get();
        }

        if ($this->sel_type == 2) {
            $data = JsonJournal::where('upload_id', $this->journal->id);

            $data = $data->where(function ($data) {
                $ctr = 1;
                foreach ($this->export_languages_arr as $lang) {
                    if ($lang['selected']) {
                        if ($ctr == 1) {
                            $data = $data->whereJsonContains('language', $lang['code']);
                        } else {
                            $data = $data->orWhereJsonContains('language', $lang['code']);
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
            'title','keywords','subject','eissn','aims_scope','language','publisher_country','publisher_name','institution_name'
        ];

        foreach ($data as $d) {
            $keywords = $d->keywords;
            $subject = $d->subject;
            $eissn = $d->eissn;
            $title = $d->title;
            $aims_scope = '';
            if ($d->ref) {
                $aims_scope = json_decode($d->ref)->aims_scope;
            }
            $language = $d->language;
            $publisher_country = '';
            $publisher_name = '';
            if ($d->publisher) {
                $publisher_country = json_decode($d->publisher)->country;
                $publisher_name = json_decode($d->publisher)->name;
            }
            $institution_name = '';
            if (!is_null($d->institution)) {
                if (isset(json_decode($d->institution)->name)) {
                    $institution_name = json_decode($d->institution)->name;
                }
            }
            $data_csv[] = [
                $title,
                $keywords,
                $subject,
                $eissn,
                $aims_scope,
                $language,
                $publisher_country,
                $publisher_name,
                $institution_name,
            ];
        }

        $fileName = $this->journal->file_name . "_CLEAN_" .  time() . '.csv';
        $fileName = $this->journal->file_name . "_CLEAN_" .  $this->export_qty_text . '.csv';

        $fileName2 = $this->journal->file_name;
        $fp = fopen(public_path('exports/' . $fileName), 'w');
        foreach ($data_csv as $fields) {
            fputcsv($fp, $fields);
        }
        fclose($fp);

        $this->export_file_name = $fileName;

        auth()->user()->logs()->create([
            'action' => 'Export file: ' . $this->export_file_name,
            'type' => 'export-journal-csv',
            'obj' => json_encode([
                'file_name' => $fileName
            ])
        ]);
    }

    public function export_new_csv() 
    {
        $this->set_export_prop_pre();

        $this->dl_clean_data_new_csv();
        return Response::download(public_path('exports/' . $this->export_file_name));
    }

    public function dl_clean_data_new_csv()
    {
        $skip = $this->export_skip;
        $take = $this->export_take;

        if ($this->sel_type == 1) {
            $data = JsonJournal::where('upload_id', $this->journal->id);

            $data = $data->where(function ($data) {
                /*$ctr = 1;
                foreach ($this->export_languages_arr as $lang) {
                    if ($lang['selected']) {
                        if ($ctr == 1) {
                            $data = $data->whereJsonContains('language', $lang['code']);
                        } else {
                            $data = $data->orWhereJsonContains('language', $lang['code']);
                        }
                        $ctr++;
                    }
                }*/
                $data = $data->whereJsonContains('language', 'EN');
                $data = $data->orWhereJsonContains('language', 'ZH');
            });


            $data = $data->skip($skip)->take($take);
            $data = $data->get();
        }

        if ($this->sel_type == 2) {
            $data = JsonJournal::where('upload_id', $this->journal->id);

            $data = $data->where(function ($data) {
                /*$ctr = 1;
                foreach ($this->export_languages_arr as $lang) {
                    if ($lang['selected']) {
                        if ($ctr == 1) {
                            $data = $data->whereJsonContains('language', $lang['code']);
                        } else {
                            $data = $data->orWhereJsonContains('language', $lang['code']);
                        }
                        $ctr++;
                    }
                }*/
                $data = $data->whereJsonContains('language', 'EN');
                $data = $data->orWhereJsonContains('language', 'ZH');
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
            'title','keywords','subject','eissn','aims_scope','language','publisher_country','publisher_name','institution_name'
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
            
            $keywords = $d->keywords;
            $subject = $d->subject;
            $eissn = $d->eissn;
            $title = $d->title;
            $aims_scope = '';
            if ($d->ref) {
                $aims_scope = json_decode($d->ref)->aims_scope;
            }
            $language = $d->language;
            $publisher_country = '';
            $publisher_name = '';
            if ($d->publisher) {
                $publisher_country = json_decode($d->publisher)->country;
                $publisher_name = json_decode($d->publisher)->name;
            }
            $institution_name = '';
            if (!is_null($d->institution)) {
                if (isset(json_decode($d->institution)->name)) {
                    $institution_name = json_decode($d->institution)->name;
                }
            }
            $data_csv[] = [
                $title,
                $keywords,
                $subject,
                $eissn,
                $aims_scope,
                $language,
                $publisher_country,
                $publisher_name,
                $institution_name,
            ];
        }

        $fileName = $this->journal->file_name . "_CLEAN_" .  time() . '.csv';
        $fileName = $this->journal->file_name . "_CLEAN_" .  $this->export_qty_text . '.csv';

        $fileName2 = $this->journal->file_name;
        $fp = fopen(public_path('exports/' . $fileName), 'w');
        foreach ($data_csv as $fields) {
            fputcsv($fp, $fields);
        }
        fclose($fp);

        $this->export_file_name = $fileName;

        auth()->user()->logs()->create([
            'action' => 'Export file: ' . $this->export_file_name,
            'type' => 'export-journal-csv',
            'obj' => json_encode([
                'file_name' => $fileName
            ])
        ]);
    }
}
