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
        Schema::create('mentorings', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal_mentoring');
            $table->string('nama_mentor');
            $table->string('tempat_mentoring');
            $table->text('materi_singkat');
            $table->string('image')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mentorings');
    }
};
