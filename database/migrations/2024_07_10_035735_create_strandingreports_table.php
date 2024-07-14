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
        Schema::create('strandingreports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('category_id')->constrained();
            $table->foreignId('province_id')->constrained();
            $table->string('location');
            $table->decimal('latitude', 11, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->foreignId('group_id')->constrained();
            $table->foreignId('species_id')->constrained()->nullable();
            $table->foreignId('quantity_id')->constrained();
            $table->integer('count', false);
            $table->foreignId('code_id')->constrained();
            $table->string('gender')->nullable();
            $table->string('informant_name')->nullable();
            $table->string('partner')->nullable();
            $table->date('information_date');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('strandingreports');
    }
};
