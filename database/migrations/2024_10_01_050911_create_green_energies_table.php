<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('green_energies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('image')->nullable(); // for storing image paths
            $table->string('short_description');
            $table->text('long_description');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('green_energies');
    }
};
