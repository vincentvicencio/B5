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
        // Corresponds to the SubTraitScore table in the diagram
        Schema::create('sub_trait_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assessment_id')
                  ->constrained('assessments', 'id')
                  ->onDelete('cascade');
            $table->foreignId('sub_trait_id')
                  ->constrained('sub_traits', 'subtrait_id')
                  ->onDelete('cascade');
            $table->decimal('score_pct', 5, 2);
            $table->string('interpretation');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    { 
        Schema::dropIfExists('sub_trait_scores');
    }
};
