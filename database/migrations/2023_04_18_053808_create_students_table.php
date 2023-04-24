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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('age');
            $table->string('image');
            $table->longText('status')->nullable();
            
            $table->foreignId('classroom_id')
            ->nullable()
            ->constrained('classrooms')
            ->onDelete('set null');
            
            $table->foreignId('sibling_id')
            ->nullable()
            ->constrained('siblings')
            ->onDelete('set null');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};