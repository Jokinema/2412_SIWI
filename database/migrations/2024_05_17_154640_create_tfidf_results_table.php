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
        Schema::create('tfidf_results', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('datasets_id');
            $table->unsignedBigInteger('pre_processings_id');
            $table->string('term');
            $table->double('tfidf', 15, 8);
            $table->timestamps();

//            $table->foreign('datasets_id')->references('id')->on('pre_processings')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tfidf_results');
    }
};
