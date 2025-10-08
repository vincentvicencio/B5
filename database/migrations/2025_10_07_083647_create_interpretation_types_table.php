<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('interpretation_types', function (Blueprint $table) {
            $table->id();
            // This name will be used in the UI (e.g., 'Sub-Trait Standard', 'Trait Standard')
            $table->string('name')->unique(); 
            $table->timestamps();
        });

        // Insert default types immediately so they are available after migration
        DB::table('interpretation_types')->insert([
            ['name' => 'Sub-Trait Standard', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Trait Standard', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('interpretation_types');
    }
};
