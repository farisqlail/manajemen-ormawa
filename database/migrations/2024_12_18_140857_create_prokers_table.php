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
        Schema::create('prokers', function (Blueprint $table) {
            $table->id();
            $table->biginteger('id_club');
            $table->string('name');
            $table->string('proposal')->nullable();
            $table->string('laporan')->nullable();
            $table->biginteger('budget');
            $table->date('target_event');
            $table->string('status');
            $table->string('status_laporan')->nullable();
            $table->longText('reason')->nullable();
            $table->string('pdf_file')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('prokers', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
