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
        Schema::create('pre_processings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('datasets_id');
            $table->foreign('datasets_id')->references('id')->on('datasets')->onDelete('cascade');
            $table->string('original')->nullable();
            $table->string('cleaned')->nullable();
            $table->string('case_folded');
            $table->string('tokenized');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pre_processings');
    }
};
