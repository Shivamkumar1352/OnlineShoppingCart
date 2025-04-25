<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations - adds soft delete column.
     */
    public function up(): void
    {
        // First check if the column already exists
        if (!Schema::hasColumn('products', 'deleted_at')) {
            Schema::table('products', function (Blueprint $table) {
                $table->softDeletes(); // Adds nullable deleted_at timestamp
            });
        }
    }

    /**
     * Reverse the migrations - removes soft delete column.
     */
    public function down(): void
    {
        if (Schema::hasColumn('products', 'deleted_at')) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropSoftDeletes(); // Removes the deleted_at column
            });
        }
    }
};
