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
        Schema::create('strandingdetails', function (Blueprint $table) {
            $table->id();
            $table->foreignId('strandingreport_id')->constrained()->cascadeOnDelete();
            $table->date('start_handling_date');
            $table->date('end_handling_date');
            $table->text('report');
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
        Schema::dropIfExists('strandingdetails');
    }
};
