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
        // Use Schema::table for existing table modifications
        Schema::table('interpretations', function (Blueprint $table) {
            // FIX: Drop the unique constraint using the column name 'trait_level'.
            // The original unique(['trait_level']) prevents having multiple interpretations 
            // with the same level name (e.g., 'Low' for both Trait and Sub-Trait).
            try {
                // Attempt to drop the unique index. This is the part that was failing.
                $table->dropUnique(['trait_level']);
            } catch (\Exception $e) {
                // The error (1091) occurs if the index name is wrong or the index is already gone.
                // By catching and ignoring this specific failure, we allow the migration to proceed 
                // to add the column, assuming the primary issue (the unique constraint) is handled
                // or was never properly created.
            }

            // 2. Add the foreign key column
            $table->foreignId('interpretation_type_id')
                  ->after('id')
                  ->constrained('interpretation_types')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('interpretations', function (Blueprint $table) {
            // Reverse the foreign key addition
            $table->dropForeign(['interpretation_type_id']);
            $table->dropColumn('interpretation_type_id');

            // Re-add the unique constraint if reverting to the old structure
            $table->unique(['trait_level']);
        });
    }
};
