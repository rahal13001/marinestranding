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
        Schema::table('kkprluses', function (Blueprint $table) {
            $table->string('subject_name')->nullable();
            $table->integer('widht',false)->nullable();
            $table->integer('length',false)->nullable();
            $table->decimal('latitude', 11, 8);
            $table->decimal('longitude', 11, 8);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kkprluses', function (Blueprint $table) {
            //
        });
    }
};
