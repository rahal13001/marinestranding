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
        Schema::create('individualdatas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('strandingreport_id')->constrained()->onDelete('cascade');
            $table->foreignId('code_id')->constrained()->onDelete('cascade');
            $table->string('gender')->nullable();
            $table->string('total_length')->nullable();
            $table->foreignId('method_id')->constrained()->onDelete('cascade');
            $table->text('ind_desc')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('individualdatas');
    }
};
