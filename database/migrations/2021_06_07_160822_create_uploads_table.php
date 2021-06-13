<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUploadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('uploads', function (Blueprint $table) {
            $table->id();
            $table->string('file_name')->unique();
            $table->string('file_type')->nullable();
            $table->string('new_file_name')->nullable();
            $table->timestamp('date_modified')->nullable();
            $table->bigInteger('original_record_count')->nullable();
            $table->bigInteger('extracted_record_count')->nullable();
            $table->bigInteger('new_record_count')->nullable();
            $table->bigInteger('updated_record_count')->nullable();
            $table->string('category')->nullable();
            $table->boolean('show')->default(true);
            $table->timestamp('import_start')->nullable();
            $table->timestamp('import_end')->nullable();
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
        Schema::dropIfExists('uploads');
    }
}
