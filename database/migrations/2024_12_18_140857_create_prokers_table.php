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
            $table->longText('proposal');
            $table->longText('laporan')->nullable();
            $table->biginteger('budget');
            $table->date('target_event');
            $table->string('status');
            $table->string('status_laporan')->nullable();
            $table->longText('reason')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prokers');
    }
};
