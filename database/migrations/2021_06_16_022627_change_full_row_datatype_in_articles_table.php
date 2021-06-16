<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeFullRowDatatypeInArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('json_articles', function (Blueprint $table) {
            $table->mediumText('full_row_obj')->change();
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
            $table->text('full_row_obj')->change();
        });
    }
}
