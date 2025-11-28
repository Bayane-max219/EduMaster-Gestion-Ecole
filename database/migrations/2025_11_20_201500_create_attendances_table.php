<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('attendances')) {
            Schema::create('attendances', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('student_id');
                $table->date('date');
                $table->enum('status', ['present','absent','late','excused'])->default('present');
                $table->text('notes')->nullable();
                $table->unsignedBigInteger('created_by')->nullable();
                $table->timestamps();

                $table->index(['student_id']);
                $table->index(['date']);
                $table->index(['status']);
            });

            Schema::table('attendances', function (Blueprint $table) {
                if (Schema::hasTable('students')) {
                    $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
                }
                if (Schema::hasTable('users')) {
                    $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
                }
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
