<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTitlesXToArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('json_articles', function (Blueprint $table) {
            $table->string('title_1')->default('');
            $table->string('title_2')->default('');
            $table->string('title_3')->default('');
            $table->index(['title_1', 'title_2', 'title_3']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('json_articles', function (Blueprint $table) {
            $table->dropColumn('title_1');
            $table->dropColumn('title_2');
            $table->dropColumn('title_3');
        });
    }
}
