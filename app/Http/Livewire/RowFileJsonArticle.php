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
    public $export_skip;

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


        $this->export_qty_category = 1; //per 10k
        //  $this->export_qty_category=2; //per 20k


        $this->sel_type = 2; //2=new,1=all,3=updated
    }



    public function dl_clean_data()
    {
        //  ini_set('memory_limit', '512M');

        $skip = $this->export_skip;
        $take = 10000;


        /*
        $data = JsonArticle::where('upload_id', $this->article->id)
            ->whereBetween('ctr', [$this->export_range_min, $this->export_range_max])
            ->get();
            */
        if ($this->sel_type == 1) {
            $data = JsonArticle::where('upload_id', $this->article->id)
                ->skip($skip)->take($take)
                ->get();
        }

        if ($this->sel_type == 2) {
            $data = JsonArticle::where('upload_id', $this->article->id)
                ->skip($skip)->take($take)->where('is_new', true)
                ->get();
        }

        if ($this->sel_type == 3) {
            $data = JsonArticle::where('upload_id', $this->article->id)
                ->skip($skip)->take($take)->where('is_updated', true)
                ->get();
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
        File::put(public_path('exports/' . $fileName), $data_file);

        $this->export_file_name = $fileName;

        auth()->user()->logs()->create([
            'action' => 'Export file: ' . $this->export_file_name
        ]);
    }

    public function read_json_article()
    {
        $this->import_force = false;
        $this->import_json();
    }
    public function read_json_article_force()
    {
        $this->import_force = true;
        $this->import_json();
    }

    public function import_json()
    {

        $this->path = storage_path('app/json/Article/') . $this->article->file_name . '.json';
        //$rows = json_decode(file_get_contents($path), true);
        $rows = JsonMachine::fromFile($this->path);



        $limit = 200000;
        $limit_ctr = 0;
        $record_ctr = 0;
        $extracted_ctr = 0;
        $record_new_ctr = 0;
        $record_updated_ctr = 0;

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
            $article->article_id = $row['id'];
            $article->upload_id = $this->article->id;
            $article->insert_tag = $this->insert_tag;


            if (isset($row['bibjson']['title'])) {
                $article->title = $row['bibjson']['title'];
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
            }

            if (isset($row['bibjson']['keywords'])) {
                $article->keywords = json_encode($row['bibjson']['keywords']);
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
                $article->is_new = true;
                $article->ctr = $ctr;
                $article->save();

                $extracted_ctr++;
            }
            $ctr++;
            $limit_ctr++;
        }


        $extracted_ctr =  JsonArticle::where('upload_id', $this->article->id)->count();
        $this->article->original_record_count = $record_ctr;
        $this->article->extracted_record_count = $extracted_ctr;
        $this->article->new_record_count = $record_new_ctr;
        $this->article->updated_record_count = $record_updated_ctr;


        $this->article->save();

        auth()->user()->logs()->create([
            'action' => 'Import file: ' . $this->article->file_name . ".json"
        ]);
    }

    private function is_subject_medical($subjects) // accepts json
    {


        $valid_subjects = [

            'Medicine',

            //----Medicine
            'Dentistry',
            'Dermatology',
            'Gynecology and obstetrics',
            'Homeopathy',
            'Internal medicine',
            'Infectious and parasitic diseases',
            'Medical emergencies. Critical care. Intensive care. First aid',
            'Neoplasms. Tumors. Oncology. Including cancer and carcinogens',
            'Neurosciences. Biological psychiatry. Neuropsychiatry',
            'Neurology. Diseases of the nervous system',
            'Psychiatry',
            'Therapeutics. Psychotherapy',
            'Special situations and conditions',
            'Arctic medicine. Tropical medicine',
            'Geriatrics',
            'Industrial medicine. Industrial hygiene',
            'Sports medicine',
            'Specialties of internal medicine',
            'Diseases of the blood and blood-forming organs',
            'Diseases of the circulatory (Cardiovascular) system',
            'Diseases of the digestive system. Gastroenterology',
            'Diseases of the endocrine glands. Clinical endocrinology',
            'Diseases of the genitourinary system. Urology',
            'Diseases of the musculoskeletal system',
            'Diseases of the respiratory system',
            'Immunologic diseases. Allergy',
            'Nutritional diseases. Deficiency diseases',

            'Medicine (General)',
            'Computer applications to medicine. Medical informatics',
            'General works',
            'History of medicine. Medical expeditions',
            'Medical philosophy. Medical ethics',
            'Medical physics. Medical radiology. Nuclear medicine',
            'Medical technology',

            'Medical',
            'Nursing',
            'Ophthalmology',
            'Other systems of medicine',

            'Chiropractic',
            'Mental healing',
            'Miscellaneous systems and treatments',
            'Osteopathy',

            'Optics',
            'Otorhinolaryngology',
            'Pathology',
            'Pediatrics',
            'Pharmacy and materia medica',
            'Public aspects of medicine',
            'Toxicology. Poisons',
            'Anesthesiology',
            'Orthopedic surgery',
            'Therapeutics. Pharmacology',
            'Philosophy. Psychology.', //no found
            'Aesthetics',
            'Psychology',
            'Consciousness. Cognition',

            'Science',
            'Biology (General)',
            'Ecology',
            'Genetics',
            'Life',
            'Reproduction',

            'Chemistry',
            'Analytical chemistry',
            'Organic chemistry',
            'Biochemistry',
            'Human anatomy',
            'Microbiology',
            'Microbial ecology',

            'Physiology',
            'Biochemistry',
            'Neurophysiology and neuropsychology',

            'Zoology'


        ];



        $subjects = json_decode($subjects);
        foreach ($subjects as $subject) {
            if (in_array($subject->term, $valid_subjects)) {
                return true;
            }
        }

        return false;
    }

    public function export()
    {

        $this->set_export_prop();

        $this->dl_clean_data();
        // $this->export_file_name .= "_CLEANx_.json";
        return Response::download(public_path('exports/' . $this->export_file_name));
    }

    private function set_export_prop()
    {
        if ($this->export_qty_category == 1) {

            if ($this->export_qty == 1) {
                $this->export_skip = 0;
                $this->export_qty_text = "1-10k";
            }

            if ($this->export_qty == 2) {
                $this->export_skip = 10000;
                $this->export_qty_text = "10-20k";
            }

            if ($this->export_qty == 3) {
                $this->export_skip = 20000;
                $this->export_qty_text = "20-30k";
            }

            if ($this->export_qty == 4) {
                $this->export_skip = 30000;
                $this->export_qty_text = "30-40k";
            }

            if ($this->export_qty == 5) {
                $this->export_skip = 40000;
                $this->export_qty_text = "40-50k";
            }
            if ($this->export_qty == 6) {
                $this->export_skip = 50000;
                $this->export_qty_text = "50-60k";
            }
            if ($this->export_qty == 7) {
                $this->export_skip = 60000;
                $this->export_qty_text = "60-70k";
            }
            if ($this->export_qty == 8) {
                $this->export_skip = 70000;
                $this->export_qty_text = "70-80k";
            }
            if ($this->export_qty == 9) {
                $this->export_skip = 80000;
                $this->export_qty_text = "80-90k";
            }
            if ($this->export_qty == 10) {
                $this->export_skip = 90000;
                $this->export_qty_text = "90-100k";
            }
        } elseif ($this->export_qty_category == 2) {

            if ($this->export_qty == 1) {
                $this->export_skip = 0;
                $this->export_qty_text = "1-20k";
            }

            if ($this->export_qty == 2) {
                $this->export_skip = 20000;
                $this->export_qty_text = "20-40k";
            }

            if ($this->export_qty == 3) {
                $this->export_skip = 40000;
                $this->export_qty_text = "40-60k";
            }

            if ($this->export_qty == 4) {
                $this->export_skip = 60000;
                $this->export_qty_text = "60-80k";
            }

            if ($this->export_qty == 5) {
                $this->export_skip = 80000;
                $this->export_qty_text = "80-100k";
            }
        }
    }




    public function render()
    {
        return view('livewire.row-file-json-article');
    }
}
