<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('payments')) {
            Schema::create('payments', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('student_id');
                $table->unsignedBigInteger('fee_id');
                $table->decimal('amount', 10, 2);
                $table->date('payment_date');
                $table->enum('payment_method', ['especes','cheque','virement','mobile_money','carte'])->default('especes');
                $table->string('reference')->nullable();
                $table->enum('status', ['pending','paid','partial','overdue','cancelled'])->default('pending');
                $table->string('receipt_number', 100)->nullable();
                $table->text('notes')->nullable();
                $table->unsignedBigInteger('created_by')->nullable();
                $table->timestamps();

                $table->index(['student_id']);
                $table->index(['fee_id']);
                $table->index(['payment_date']);
            });

            Schema::table('payments', function (Blueprint $table) {
                if (Schema::hasTable('students')) {
                    $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
                }
                if (Schema::hasTable('fees')) {
                    $table->foreign('fee_id')->references('id')->on('fees')->onDelete('cascade');
                }
                if (Schema::hasTable('users')) {
                    $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
                }
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
