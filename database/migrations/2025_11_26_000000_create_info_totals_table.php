<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('info_totals', function (Blueprint $table) {
            $table->id();
            $table->string('spec_version')->nullable();
            $table->dateTime('exported_at')->nullable();
            $table->integer('vehicles')->default(0);
            $table->integer('parties')->default(0);
            $table->integer('users')->default(0);
            $table->integer('cases')->default(0);
            $table->integer('inspections')->default(0);
            $table->integer('documents')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('info_totals');
    }
};