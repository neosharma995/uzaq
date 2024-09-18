<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFootersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('footers', function (Blueprint $table) {
            $table->id();
            $table->string('column_1_heading_1');
            $table->string('column_1_field_1')->nullable();
            $table->string('column_1_field_2')->nullable();
            $table->string('column_1_field_3')->nullable();
            $table->string('column_1_field_4')->nullable();
            $table->string('column_2_heading_1');
            $table->string('column_2_field_1')->nullable();
            $table->string('column_2_field_2')->nullable();
            $table->string('column_2_field_3')->nullable();
            $table->string('column_3_heading_1');
            $table->string('column_3_field_1')->nullable();
            $table->string('column_3_field_2')->nullable();
            $table->string('column_3_field_3')->nullable();
            $table->timestamps();  // To track when the footer data was created or updated
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('footers');
    }
}
