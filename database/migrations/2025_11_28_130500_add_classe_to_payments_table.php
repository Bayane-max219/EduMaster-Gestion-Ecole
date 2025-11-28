<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('payments') && !Schema::hasColumn('payments', 'classe')) {
            Schema::table('payments', function (Blueprint $table) {
                // Classe de l'élève au moment du paiement (snapshot texte)
                $table->string('classe', 100)->nullable()->after('student_id');
                $table->index('classe');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('payments') && Schema::hasColumn('payments', 'classe')) {
            Schema::table('payments', function (Blueprint $table) {
                $table->dropIndex('payments_classe_index');
                $table->dropColumn('classe');
            });
        }
    }
};
