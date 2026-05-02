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
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->foreignID('user_id')->constrained()->onDelete('cascade');;
            $table->string('car_image');
            $table->date('date_owned');
            $table->string('brand');
            $table->string('model');
            $table->decimal('price', 10, 2); 
            $table->integer('rent_value')->default(1);
            $table->string('rent_unit')->default('Day');
            $table->string('transmission');
            $table->string('fuel_type');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cars');
    }
};
