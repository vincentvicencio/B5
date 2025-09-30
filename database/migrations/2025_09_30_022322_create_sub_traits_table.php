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
        Schema::create('sub_traits', function (Blueprint $table) { 
            $table->id();   
            $table->string('subtrait_name');   
            $table->integer('max_raw_score');
            $table->foreignId('trait_id')->constrained()->onDelete('cascade');     
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sub_traits');
    }
};
