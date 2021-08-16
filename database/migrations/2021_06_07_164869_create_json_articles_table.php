<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJsonArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {


        Schema::create('json_articles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('upload_id')->constrained()->onDelete('cascade');
            $table->integer('ctr')->nullable();
            $table->string('article_id')->nullable()->unique();
            $table->text('title')->nullable();
            $table->text('slug')->nullable();
            $table->string('title_short')->nullable();
            $table->index('title_short');
            $table->text('abstract')->nullable();
            $table->text('identifier_list')->nullable();
            $table->mediumText('author_list')->nullable();
            $table->text('link_list')->nullable();

            //Journal
            $table->text('journal_volume')->nullable();
            $table->text('journal_number')->nullable();
            $table->text('journal_country')->nullable();
            $table->text('journal_license')->nullable();
            $table->text('journal_issns')->nullable();
            $table->text('journal_publisher')->nullable();
            $table->text('journal_language')->nullable();
            $table->text('journal_title')->nullable();

            $table->string('year')->nullable();
            $table->string('month')->nullable();

            $table->string('start_page')->nullable();
            $table->text('subject')->nullable();
            $table->text('subject_orig')->nullable();

            $table->text('keywords')->nullable();
            $table->text('keywords_orig')->nullable();
            $table->text('insert_tag')->nullable();


            $table->timestamp('last_updated')->nullable();
            $table->timestamp('created_date')->nullable();

            //custom by me: Richard 

            $table->boolean('is_new')->default(false);
            $table->boolean('is_updated')->default(false);

            $table->text('full_row_obj')->nullable();


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
        Schema::dropIfExists('json_articles');
    }
}
