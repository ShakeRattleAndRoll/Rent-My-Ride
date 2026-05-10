<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('cars', 'auto_accept_priority')) {
            Schema::table('cars', function (Blueprint $table) {
                $table->string('auto_accept_priority')->default('first_pending')->after('auto_accept');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('cars', 'auto_accept_priority')) {
            Schema::table('cars', function (Blueprint $table) {
                $table->dropColumn('auto_accept_priority');
            });
        }
    }
};
