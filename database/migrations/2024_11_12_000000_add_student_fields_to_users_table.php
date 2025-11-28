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
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                if (!Schema::hasColumn('users', 'classe')) {
                    $table->string('classe')->nullable()->after('phone');
                }
                if (!Schema::hasColumn('users', 'date_naissance')) {
                    $table->date('date_naissance')->nullable()->after('classe');
                }
                if (!Schema::hasColumn('users', 'parent_tuteur')) {
                    $table->string('parent_tuteur')->nullable()->after('date_naissance');
                }
                if (!Schema::hasColumn('users', 'adresse')) {
                    $table->text('adresse')->nullable()->after('parent_tuteur');
                }
                if (!Schema::hasColumn('users', 'is_active')) {
                    $table->boolean('is_active')->default(true)->after('adresse');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                $columns = ['classe', 'date_naissance', 'parent_tuteur', 'adresse', 'is_active'];
                foreach ($columns as $col) {
                    if (Schema::hasColumn('users', $col)) {
                        $table->dropColumn($col);
                    }
                }
            });
        }
    }
};
