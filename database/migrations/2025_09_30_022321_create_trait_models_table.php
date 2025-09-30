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
        Schema::create('traits', function (Blueprint $table) {
            
            $table->id(); 
          
            $table->string('title');
           
            $table->text('description');
            
            $table->string('trait_display_color', 7);
            // max_raw_score (int)
            $table->integer('max_raw_score');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('traits');
    }
};
