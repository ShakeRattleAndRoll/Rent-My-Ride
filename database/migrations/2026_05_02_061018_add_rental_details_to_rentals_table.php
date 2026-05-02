<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rentals', function (Blueprint $table) {
            $table->integer('days')->default(1)->after('end_date');
            $table->string('rent_unit')->default('Day')->after('days');
            $table->decimal('total_price', 10, 2)->default(0)->after('rent_unit');
        });
    }

    public function down(): void
    {
        Schema::table('rentals', function (Blueprint $table) {
            $table->dropColumn(['days', 'rent_unit', 'total_price']);
        });
    }
};