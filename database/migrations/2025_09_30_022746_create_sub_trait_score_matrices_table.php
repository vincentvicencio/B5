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
        Schema::create('sub_trait_score_matrices', function (Blueprint $table) {
            
            $table->id(); 
            $table->foreignId('subtrait_id')->constrained('sub_traits')->onDelete('cascade'); 
            $table->integer('min_score');      
            $table->integer('max_score');
            $table->foreignId('interpretation_id')->constrained('interpretations')->onDelete('cascade');     
            $table->timestamps();

            $table->unique(['subtrait_id', 'min_score', 'max_score']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sub_trait_score_matrices');
    }
};
