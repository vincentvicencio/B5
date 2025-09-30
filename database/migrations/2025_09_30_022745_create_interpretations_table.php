<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('interpretations', function (Blueprint $table) {
            $table->id();
            $table->string('trait_level');
            $table->text('interpretation');
            $table->timestamps();
            $table->unique(['trait_level']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('interpretations');
    }
};
