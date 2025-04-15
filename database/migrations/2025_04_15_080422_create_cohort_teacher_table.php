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
        Schema::create('cohort_teacher', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cohort_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            $table->unique(['cohort_id', 'user_id'], 'cohort_teacher_unique');

            // Définir les clés étrangères
            $table->foreign('cohort_id')
                ->references('id')
                ->on('cohorts')
                ->onDelete('cascade');
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cohort_teacher');
    }
};
