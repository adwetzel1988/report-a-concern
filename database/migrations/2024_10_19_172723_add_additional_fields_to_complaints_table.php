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
        Schema::table('complaints', function (Blueprint $table) {
            $table->string('address')->nullable();
            $table->string('person_name')->nullable();
            $table->string('person_number')->nullable();
            $table->string('person_email')->nullable();

            // remove city_id
            $table->dropForeign(['city_id']);
            $table->dropColumn('city_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('complaints', function (Blueprint $table) {
            $table->dropColumn('address');
            $table->dropColumn('person_name');
            $table->dropColumn('person_number');
            $table->dropColumn('person_email');

            // add city_id
            $table->foreignId('city_id')->references('id')->on('cities')->onDelete('cascade');
        });
    }
};
