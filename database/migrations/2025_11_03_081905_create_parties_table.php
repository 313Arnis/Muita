<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('parties', function (Blueprint $table) {
            $table->id();
            $table->string('external_id')->unique(); // JSON "id"
            $table->enum('type', ['person', 'company', 'organization']);
            $table->string('name', 150);
            $table->string('reg_code')->nullable();
            $table->string('vat')->nullable();
            $table->string('country', 2);
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('parties');
    }
};