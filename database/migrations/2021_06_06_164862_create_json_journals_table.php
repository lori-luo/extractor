<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJsonJournalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->down();
        Schema::create('json_journals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('upload_id')->constrained()->onDelete('cascade');
            $table->integer('ctr')->nullable();
            $table->string('journal_id')->nullable()->unique();
            $table->text('editorial')->nullable();
            $table->text('pid_scheme')->nullable();
            $table->text('copyright')->nullable();
            $table->text('keywords')->nullable();
            $table->text('keywords_orig')->nullable();

            $table->text('plagiarism')->nullable();
            $table->text('subject')->nullable();
            $table->text('subject_orig')->nullable();
            $table->text('eissn')->nullable();
            $table->text('language')->nullable();

            $table->text('title')->nullable();
            $table->text('article')->nullable();
            $table->text('institution')->nullable();
            $table->text('preservation')->nullable();
            $table->text('license')->nullable();
            $table->text('ref')->nullable();
            $table->text('apc')->nullable();
            $table->text('other_charges')->nullable();
            $table->text('publication_time_weeks')->nullable();
            $table->text('deposit_policy')->nullable();
            $table->text('publisher')->nullable();
            $table->text('boai')->nullable();
            $table->text('waiver')->nullable();
            $table->text('admin')->nullable();










            $table->dateTime('last_updated')->nullable();
            $table->dateTime('created_date')->nullable();

            //custom by me: Richard 

            $table->boolean('is_new')->default(false);
            $table->boolean('is_updated')->default(false);
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
        Schema::dropIfExists('json_journals');
    }
}
