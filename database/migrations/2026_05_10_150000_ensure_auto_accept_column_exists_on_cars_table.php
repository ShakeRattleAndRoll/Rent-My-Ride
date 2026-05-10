<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('cars', 'auto_accept')) {
            Schema::table('cars', function (Blueprint $table) {
                $table->boolean('auto_accept')->default(false);
            });
        }
    }

    public function down(): void
    {
        // No-op: this migration only repairs databases where the original
        // migration was marked as ran before the column actually existed.
    }
};
