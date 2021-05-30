<?php

namespace App\Http\Livewire;

use App\Models\Upload;
use Livewire\Component;
use App\Models\XmlPubMed;

use Illuminate\Support\Str;
use Prewk\XmlStringStreamer;
use Illuminate\Support\Carbon;
use Prewk\XmlStringStreamer\Parser;
use Prewk\XmlStringStreamer\Stream;

class XmlFileRowAction extends Component
{
    public $txt;
    public $xml;

    public function mount()
    {
        $this->txt = "";
    }

    public function read_xml()
    {
        $this->txt = "";

        $this->emit('importing');

        $path = storage_path('app') . "\\" . $this->xml->file_type . "\\" . $this->xml->new_file_name;

        // Prepare our stream to be read with a 1kb buffer
        $stream = new Stream\File($path, 1024);


        // Construct the default parser (StringWalker)
        $parser = new Parser\StringWalker();

        // Create the streamer
        $streamer = new XmlStringStreamer($parser, $stream);

        $ctr = 0;

        $bulk_rows = [];
        $bulk_limit = 10;
        $bulk_ctr = 0;

        // Iterate through the `<customer>` nodes
        while ($node = $streamer->getNode()) {
            // $node will be a string like this: "<customer><firstName>Jane</firstName><lastName>Doe</lastName></customer>"
            $simpleXmlNode = simplexml_load_string($node);


            $art = new XmlPubMed();



            $root = $simpleXmlNode->MedlineCitation;
            $article = $root->Article;

            $new_row['status'] = $root->attributes()->Status;
            $new_row['owner'] = $root->attributes()->Owner;
            $new_row['pmid'] = $root->PMID;
            $new_row['version'] = $root->PMID->attributes()->Version;

            $_date_completed = $root->DateCompleted;
            $new_row['date_completed'] = $_date_completed->Year . '-' . $_date_completed->Month . '-' . $_date_completed->Day;
            $_date_revised = $root->DateRevised;
            $new_row['date_revised'] = $_date_revised->Year . '-' . $_date_revised->Month . '-' . $_date_revised->Day;


            $new_row['pub_model'] = $article->attributes()->PubModel;

            if ($article->Journal->ISSN) {
                $new_row['issn'] = $article->Journal->ISSN;
                $new_row['issntype'] = $article->Journal->ISSN->attributes()->IssnType;
            }



            $new_row['cited_medium'] = $article->Journal->JournalIssue->attributes()->CitedMedium;
            $new_row['volume'] = $article->Journal->JournalIssue->Volume;
            $new_row['issue'] = $article->Journal->JournalIssue->Issue;

            $_pub_date = $article->Journal->JournalIssue->PubDate;
            $new_row['pub_date'] = $_pub_date->Month . ' ' . $_pub_date->Year;
            $new_row['title'] = $article->Journal->Title;
            $new_row['iso_abbreviation'] = $article->Journal->ISOAbbreviation;
            $new_row['article_title'] = $article->ArticleTitle;
            $new_row['medlinepgn'] = $article->Pagination->MedlinePgn;

            if ($article->AuthorList) {
                $new_row['authorlist_complete_yn'] =  $article->AuthorList->attributes()->CompleteYN;
            }

            $new_row['language'] = $article->Language;

            if ($root->MedlineJournalInfo) {
                $new_row['country'] = $root->MedlineJournalInfo->Country;
                $new_row['medline_ta'] = $root->MedlineJournalInfo->MedlineTA;
                $new_row['nlm_unique_id'] = $root->MedlineJournalInfo->NlmUniqueID;
                $new_row['issn_linking'] = $root->MedlineJournalInfo->ISSNLinking;
            }

            $new_row['citation_subset'] = $root->CitationSubset;
            $new_row['author_list'] = $this->col_med_article_authorlist($article);
            $new_row['grant_list'] = $this->col_med_article_grantlist($article);
            $new_row['pub_type_list'] = $this->col_med_article_pubtype_list($article);
            $new_row['chemical_list'] = $this->col_med_article_chemicals($root);
            $new_row['mesh_heading_list'] = $this->col_med_article_mesh_headings($root);


            $root = $simpleXmlNode->PubmedData;

            $new_row['history'] = $this->col_med_article_history($root);
            $new_row['publication_status'] = $root->PublicationStatus;
            $new_row['article_id_list'] =  $this->col_med_article_id_list($root);

            $new_row['created_at'] =  Carbon::now()->toDateTimeString();
            $new_row['updated_at'] =  Carbon::now()->toDateTimeString();

            array_push($bulk_rows, $new_row);

            if ($bulk_ctr++ % $bulk_limit == 0) {
                XmlPubMed::insert($bulk_rows);
                $bulk_rows = [];
            }


            if ($ctr++ == 1000) {
                break;
            }
        }



        $this->txt = "DONE";
    }

    private function col_med_article_id_list($root)
    {
        $id_list = "";
        if ($root->ArticleIdList) {

            foreach ($root->ArticleIdList->ArticleId as $article_id) {
                $id_list .= "(Article_ID:" . $article_id
                    . ",Type:" . $article_id->attributes()->IdType
                    . ")\n";
            }
        }

        return $id_list;
    }

    private function col_med_article_history($root)
    {
        $history = "";
        if ($root->History) {

            foreach ($root->History->PubMedPubDate as $history_pub_date) {
                $_date = $history_pub_date;
                $history .= "(Date:" . $_date->Year . '-' . $_date->Month . '-' . $_date->Day
                    . ",Status:" . $history_pub_date->attributes()->PubStatus
                    . ")\n";
            }
        }

        return $history;
    }

    private function col_med_article_mesh_headings($root)
    {
        $mesh_headings = "";
        if ($root->MeshHeadingList) {
            foreach ($root->MeshHeadingList->MeshHeading as $heading) {
                $mesh_headings .= "(Descriptor_Name:" . ($heading->DescriptorName ? $heading->DescriptorName : '')
                    . ",Descriptor_Name_UI:" . ($heading->DescriptorName ? $heading->DescriptorName->attributes()->UI : '')
                    . ",Major_Topic:" . ($heading->DescriptorName ? $heading->DescriptorName->attributes()->MajorTopicYN : '') . "";
                $qualifier_names = "";
                foreach ($root->MeshHeadingList->MeshHeading->QualifierName as $qualifier_name) {
                    $qualifier_names .= $qualifier_name . ",";
                }

                $mesh_headings .= ",Qualifier_Names:" . $qualifier_names . ")\n";
            }
        }

        return $mesh_headings;
    }



    private function col_med_article_chemicals($root)
    {

        $chemical_list = "";
        if ($root->ChemicalList) {
            foreach ($root->ChemicalList->Chemical as $chemical) {
                $chemical_list .= "(Registry_Number:" . $chemical->RegistryNumber
                    . ",Name_of_Substance:" . $chemical->NameOfSubstance
                    . ",UI:" . $chemical->NameOfSubstance->attributes()->UI . ")\n";
            }
        }

        return $chemical_list;
    }



    private function col_med_article_authorlist($article)
    {
        $author_list = "";
        if ($article->AuthorList) {

            foreach ($article->AuthorList->Author as $author) {

                $author_list .= ($author->LastName . ',' . $author->ForeName) . "\n";
                /*
                $art->authors()->create([
                    'valid_yn' => $author->attributes()->ValidYN,
                    'last_name' => $author->LastName,
                    'fore_name' => $author->ForeName,
                    'initials' => $author->Initials,
                ]);
                */
            }
        }

        return $author_list;
    }



    private function col_med_article_grantlist($article)
    {

        $grant_list = "";
        if ($article->GrantList->Grant) {
            foreach ($article->GrantList->Grant as $grant) {

                $grant_list .= "(ID:" . $grant->GrantID
                    . ",Agency:" . $grant->Agency
                    . ",Country:" . $grant->Country . ")\n";
            }
        }

        return $grant_list;
    }



    private function col_med_article_pubtype_list($article)
    {

        $pub_type_list = "";
        if ($article->PublicationTypeList->PublicationType) {
            foreach ($article->PublicationTypeList->PublicationType as $pubtype) {

                $pub_type_list .= "(UI:"
                    . $pubtype->attributes()->UI
                    . ",Publication_Type:" . $pubtype . ")\n";
            }
        }

        return $pub_type_list;
    }




    public function render()
    {
        return view('livewire.xml-file-row-action');
    }
}
