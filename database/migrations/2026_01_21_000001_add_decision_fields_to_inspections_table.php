<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('inspections', function (Blueprint $table) {
            $table->string('decision')->nullable()->after('assigned_to');
            $table->string('decision_by')->nullable()->after('decision');
            $table->text('decision_notes')->nullable()->after('decision_by');
            $table->timestamp('decision_ts')->nullable()->after('decision_notes');
        });
    }

    public function down(): void
    {
        Schema::table('inspections', function (Blueprint $table) {
            $table->dropColumn(['decision', 'decision_by', 'decision_notes', 'decision_ts']);
        });
    }
};
