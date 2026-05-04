<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('rentals', function (Blueprint $table) {
            $table->string('snap_brand')->nullable();
            $table->string('snap_model')->nullable();
            $table->string('snap_car_image')->nullable();
            $table->decimal('snap_price', 10, 2)->nullable();
            $table->string('snap_rent_unit')->nullable();
            $table->string('snap_fuel_type')->nullable();
            $table->string('snap_transmission')->nullable();
            $table->date('snap_date_owned')->nullable();
        });
    }

    public function down()
    {
        Schema::table('rentals', function (Blueprint $table) {
            $table->dropColumn([
                'snap_brand',
                'snap_model',
                'snap_car_image',
                'snap_price',
                'snap_rent_unit',
                'snap_fuel_type',
                'snap_transmission',
                'snap_date_owned',
            ]);
        });
    }
};