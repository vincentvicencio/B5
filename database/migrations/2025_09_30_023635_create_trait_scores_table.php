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
        Schema::create('trait_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assessment_id')
                  ->constrained('assessments', 'id')
                  ->onDelete('cascade');
            $table->foreignId('trait_id')
                  ->constrained('traits', 'trait_id')
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
        Schema::dropIfExists('trait_scores');
    }
};
