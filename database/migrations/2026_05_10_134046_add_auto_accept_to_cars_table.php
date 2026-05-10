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
        if (! Schema::hasColumn('cars', 'auto_accept')) {
            Schema::table('cars', function (Blueprint $table) {
                $table->boolean('auto_accept')->default(false);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('cars', 'auto_accept')) {
            Schema::table('cars', function (Blueprint $table) {
                $table->dropColumn('auto_accept');
            });
        }
    }
};
