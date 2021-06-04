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

    public $row_count;




    public function mount()
    {
        $this->export_range_min = 1;
        $this->export_range_max = 100;
        $this->export_qty = 1;

        $this->row_count = 0;
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
        File::put(public_path('exports/' . $fileName), $data_file);

        $this->export_file_name = $fileName;

        auth()->user()->logs()->create([
            'action' => 'Export file: ' . $this->export_file_name
        ]);
    }

    public function read_json_article()
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

        $ctr = 1;

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
            $article->last_updated = date('Y-m-d h:i:s', strtotime($row['last_updated']));

            if ($article_exists) {
                //compare the last update date
                if (
                    date('Y-m-d h:i:s', strtotime($row['last_updated'])) > $last_updated
                ) {

                    $article->save();
                    $record_updated_ctr++;
                    $extracted_ctr++;
                }
            } else { //not exists,means new
                $article->save();
                $extracted_ctr++;
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





            $new_row['last_updated'] = date('Y-m-d h:i:s', strtotime($row['last_updated']));
            $new_row['created_date'] = date('Y-m-d h:i:s', strtotime($row['created_date']));
            $new_row['created_at'] = Carbon::now();
            $new_row['updated_at'] = Carbon::now();
            $new_row['ctr'] = $ctr;


            array_push($data, $new_row);


            if ($ctr++ % 1 === 0) {
                // JsonArticle::where('article_id',  $new_row['article_id'])->delete();
                // JsonArticle::insertOrIgnore($data);
                // JsonArticle::insert($data); // Eloquent approach

                $data = [];
            }








            $limit_ctr++;
        }

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
        return Response::download(public_path('exports/' . $this->export_file_name));
    }




    public function render()
    {
        return view('livewire.row-file-json-article');
    }
}
