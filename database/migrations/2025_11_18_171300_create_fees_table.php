<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('fees')) {
            Schema::create('fees', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->text('description')->nullable();
                $table->decimal('amount', 10, 2);
                $table->enum('type', ['inscription', 'scolarite', 'cantine', 'transport', 'activite', 'autre']);
                $table->enum('frequency', ['unique', 'mensuel', 'trimestriel', 'semestriel', 'annuel'])->default('unique');
                $table->string('class_level', 100)->nullable();
                $table->boolean('is_mandatory')->default(true);
                $table->date('due_date')->nullable();
                $table->unsignedBigInteger('school_year_id');
                $table->timestamps();

                $table->index(['school_year_id']);
            });

            Schema::table('fees', function (Blueprint $table) {
                if (Schema::hasTable('school_years')) {
                    $table->foreign('school_year_id')->references('id')->on('school_years')->onDelete('cascade');
                }
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('fees');
    }
};
