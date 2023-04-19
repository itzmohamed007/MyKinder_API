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
        Schema::create('classrooms', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('activity')->nullable();

            // $table->foreignId('teacher_id')
            //     ->constrained('teachers')
            //     ->onDelete('set null');
            $table->unsignedBigInteger('teacher_id');
            $table->foreign("teacher_id")->on("teachers")->references("id")->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('classrooms');
    }
};
