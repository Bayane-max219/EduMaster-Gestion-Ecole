<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('grades')) {
            Schema::create('grades', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('student_id');
                $table->unsignedBigInteger('subject_id');
                $table->unsignedBigInteger('teacher_id');
                $table->unsignedBigInteger('class_id');
                $table->enum('exam_type', ['devoir','controle','examen','oral','pratique']);
                $table->string('exam_name');
                $table->decimal('score', 5, 2)->nullable();
                $table->decimal('max_score', 5, 2)->default(20.00);
                $table->date('exam_date');
                $table->enum('semester', ['1','2']);
                $table->unsignedBigInteger('school_year_id');
                $table->text('notes')->nullable();
                $table->timestamps();

                $table->index(['student_id']);
                $table->index(['subject_id']);
                $table->index(['teacher_id']);
                $table->index(['class_id']);
                $table->index(['school_year_id']);
            });

            // Add foreign keys if referenced tables exist
            Schema::table('grades', function (Blueprint $table) {
                if (Schema::hasTable('students')) {
                    $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
                }
                if (Schema::hasTable('subjects')) {
                    $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('cascade');
                }
                if (Schema::hasTable('teachers')) {
                    $table->foreign('teacher_id')->references('id')->on('teachers')->onDelete('cascade');
                }
                if (Schema::hasTable('class_rooms')) {
                    $table->foreign('class_id')->references('id')->on('class_rooms')->onDelete('cascade');
                }
                if (Schema::hasTable('school_years')) {
                    $table->foreign('school_year_id')->references('id')->on('school_years')->onDelete('cascade');
                }
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('grades');
    }
};
