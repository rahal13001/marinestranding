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
        Schema::create('maps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('strandingreport_id')->constrained()->cascadeOnDelete();
            $table->foreignId('province_id')->constrained();
            $table->string('location');
            $table->decimal('latitude', 11, 8);
            $table->decimal('longitude', 11, 8);
            $table->foreignId('group_id')->constrained();
            $table->foreignId('species_id')->constrained();
            $table->foreignId('category_id')->constrained();
            $table->date('information_date');
            $table->string('map_slug');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maps');
    }
};
