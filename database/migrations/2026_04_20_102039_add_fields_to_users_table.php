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
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn('name');
        $table->string('username')->unique()->after('id');
        $table->string('first_name')->after('username');
        $table->string('middle_name')->nullable()->after('first_name');
        $table->string('last_name')->after('middle_name');
        $table->date('dob')->nullable()->after('last_name');
        $table->string('sex')->nullable()->after('dob');
        $table->text('address')->nullable()->after('sex');
        $table->string('contact_number')->nullable()->after('address');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn([
            'username',
            'first_name',
            'middle_name',
            'last_name',
            'dob',
            'sex',
            'address',
            'contact_number'
        ]);
    });
}
};
