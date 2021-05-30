<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateXmlPubMedsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('xml_pub_meds', function (Blueprint $table) {
            $table->id();
            //MedlineCitation
            //attributes
            $table->string('status')->nullable();
            $table->string('owner')->nullable();
            //MedlineCitation


            //MedlineCitation->PMID
            //values
            $table->string('pmid')->nullable();
            //attributes
            $table->string('version')->nullable();
            //MedlineCitation->PMID

            //MedlineCitation->DateCompleted
            //values
            $table->string('date_completed')->nullable();
            //MedlineCitation->DateCompleted

            //MedlineCitation->DateRevised
            //values
            $table->string('date_revised')->nullable();
            //MedlineCitation->DateRevised 

            //MedlineCitation->Article
            //attributes
            $table->string('pub_model')->nullable(); //PubModel
            //MedlineCitation->Article 


            //MedlineCitation->Article->Journal->ISSN
            //values
            $table->string('issn')->nullable();
            //attributes
            $table->string('issntype')->nullable(); //IssnType
            //MedlineCitation->Article->Journal->ISSN

            //MedlineCitation->Article->Journal->JournalIssue 
            //attributes
            $table->string('cited_medium')->nullable(); //CitedMedium
            //MedlineCitation->Article->Journal->JournalIssue

            //MedlineCitation->Article->Journal->JournalIssue->Volume
            //values
            $table->string('volume')->nullable();
            //MedlineCitation->Article->Journal->JournalIssue->Volume

            //MedlineCitation->Article->Journal->JournalIssue->Issue
            //values
            $table->string('issue')->nullable();
            //MedlineCitation->Article->Journal->JournalIssue->Issue

            //MedlineCitation->Article->Journal->JournalIssue->PubDate
            //values
            $table->string('pub_date')->nullable();
            //MedlineCitation->Article->Journal->JournalIssue->PubDate

            //MedlineCitation->Article->Journal->Title
            //values
            $table->text('title')->nullable();
            //MedlineCitation->Article->Journal->Title

            //MedlineCitation->Article->Journal->ISOAbbreviation
            //values
            $table->text('iso_abbreviation')->nullable();
            //MedlineCitation->Article->Journal->ISOAbbreviation

            //MedlineCitation->Article->ArticleTitle
            //values
            $table->text('article_title')->nullable();
            //MedlineCitation->Article->ArticleTitle

            //MedlineCitation->Article->Pagination->MedlinePgn
            //values
            $table->text('medlinepgn')->nullable(); //MedlinePgn
            //MedlineCitation->Article->Pagination->MedlinePgn

            //MedlineCitation->Article->AuthorList
            //values
            $table->text('author_list')->nullable(); //AuthorList
            //MedlineCitation->Article->AuthorList

            //MedlineCitation->Article->AuthorList
            //attributes
            $table->text('authorlist_complete_yn')->nullable(); //<AuthorList CompleteYN="Y">
            //MedlineCitation->Article->AuthorList

            //MedlineCitation->Article->Language
            //values
            $table->text('language')->nullable();
            //MedlineCitation->Article->Language

            //MedlineCitation->Article->GrantList
            //values
            $table->text('grant_list')->nullable(); //GrantList
            //MedlineCitation->Article->GrantList

            //MedlineCitation->Article->PublicationTypeList
            //values
            $table->text('pub_type_list')->nullable(); //PublicationTypeList
            //MedlineCitation->Article->PublicationTypeList

            //MedlineCitation->MedlineJournalInfo->Country
            //values
            $table->text('country')->nullable();
            //MedlineCitation->MedlineJournalInfo->Country

            //MedlineCitation->MedlineJournalInfo->MedlineTA
            //values
            $table->text('medline_ta')->nullable();
            //MedlineCitation->MedlineJournalInfo->MedlineTA

            //MedlineCitation->MedlineJournalInfo->NlmUniqueID
            //values
            $table->text('nlm_unique_id')->nullable();
            //MedlineCitation->MedlineJournalInfo->NlmUniqueID

            //MedlineCitation->MedlineJournalInfo->ISSNLinking
            //values
            $table->text('issn_linking')->nullable();
            //MedlineCitation->MedlineJournalInfo->ISSNLinking

            //MedlineCitation->ChemicalList
            //values
            $table->text('chemical_list')->nullable(); //ChemicalList
            //MedlineCitation->ChemicalList

            //MedlineCitation->CitationSubset
            //values
            $table->text('citation_subset')->nullable(); //CitationSubset
            //MedlineCitation->CitationSubset

            //MedlineCitation->MeshHeadingList
            //values
            $table->text('mesh_heading_list')->nullable(); //MeshHeadingList
            //MedlineCitation->MeshHeadingList

            //PubmedData/History
            //values
            $table->text('history')->nullable();
            //PubmedData/History

            //PubmedData/PublicationStatus
            //values
            $table->text('publication_status')->nullable();
            //PubmedData/PublicationStatus

            //PubmedData/ArticleIdList
            //values
            $table->text('article_id_list')->nullable();
            //PubmedData/ArticleIdList

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('xml_pub_meds');
    }
}
