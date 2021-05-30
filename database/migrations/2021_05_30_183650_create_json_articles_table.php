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
        $this->down();
        Schema::create('json_articles', function (Blueprint $table) {
            $table->id();
            $table->string('article_id')->nullable();
            $table->text('title')->nullable();
            $table->text('abstract')->nullable();
            $table->string('identifier_list')->nullable();
            $table->mediumText('author_list')->nullable();

            //Journal
            $table->text('journal_volume')->nullable();
            $table->text('journal_number')->nullable();
            $table->text('journal_country')->nullable();
            $table->text('journal_license')->nullable();
            $table->text('journal_issns')->nullable();
            $table->text('journal_publisher')->nullable();
            $table->text('journal_language')->nullable();
            $table->text('journal_title')->nullable();


            $table->string('last_updated')->nullable();
            $table->string('created_date')->nullable();
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
