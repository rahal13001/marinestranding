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
            $table->integer('count', false);
            $table->string('gender')->nullable();
            $table->string('informant_name')->nullable();
            $table->string('partner')->nullable();
            $table->string('title')->nullable();
            $table->date('start_handling_date')->nullable();
            $table->date('end_handling_date')->nullable();
            $table->text('report')->nullable();
            $table->string('documentation1')->nullable();
            $table->string('documentation2')->nullable();
            $table->string('documentation3')->nullable();
            $table->string('documentation4')->nullable();
            $table->string('documentation5')->nullable();
            $table->string('st')->nullable();
            $table->string('other')->nullable();
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
