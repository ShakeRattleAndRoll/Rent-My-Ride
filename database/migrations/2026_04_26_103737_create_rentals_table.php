<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
    {
        Schema::create('rentals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('car_id')->constrained();
            $table->string('status')->default('pending');
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();
            $table->integer('days')->default(1);
            $table->string('rent_unit')->default('Day');
            $table->decimal('total_price', 10, 2)->default(0);
            $table->boolean('hidden_by_renter')->default(false);
            $table->boolean('hidden_by_owner')->default(false);
            $table->string('snap_brand')->nullable();
            $table->string('snap_model')->nullable();
            $table->string('snap_car_image')->nullable();
            $table->decimal('snap_price', 10, 2)->nullable();
            $table->string('snap_rent_unit')->nullable();
            $table->string('snap_fuel_type')->nullable();
            $table->string('snap_transmission')->nullable();
            $table->date('snap_date_owned')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rentals');
    }
};
