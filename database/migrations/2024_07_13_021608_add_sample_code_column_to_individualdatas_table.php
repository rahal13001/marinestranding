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
        Schema::table('individualdatas', function (Blueprint $table) {
            $table->string('sample_code')->nullable();
            $table->string('sample_doc1')->nullable();
            $table->string('sample_doc2')->nullable();
            $table->string('sample_doc3')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('individualdatas', function (Blueprint $table) {
            $table->dropColumn('sample_code');
            $table->dropColumn('sample_doc1');
            $table->dropColumn('sample_doc2');
            $table->dropColumn('sample_doc3');
        });
    }
};
