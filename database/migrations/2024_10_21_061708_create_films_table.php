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
        Schema::create('films', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description'); 
            $table->string('duration');
            $table->string('cast');
            $table->string('image');
            $table->string('link');
            $table->string('rating'); 
            $table->timestamps();
        });        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('films');
    }
};
