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
        Schema::table('strandingreports', function (Blueprint $table) {
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
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('strandingreports', function (Blueprint $table) {
            $table->dropColumn('start_handling_date');
            $table->dropColumn('end_handling_date');
            $table->dropColumn('report');
            $table->dropColumn('documentation1');
            $table->dropColumn('documentation2');
            $table->dropColumn('documentation3');
            $table->dropColumn('documentation4');
            $table->dropColumn('documentation5');
            $table->dropColumn('st');
            $table->dropColumn('other');
        });
    }
};
