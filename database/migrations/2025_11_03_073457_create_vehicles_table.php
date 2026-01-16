<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('external_id')->unique(); // JSON "id"
            $table->string('plate_no')->unique();
            $table->string('country', 2);
            $table->string('make', 50);
            $table->string('model', 50);
            $table->string('vin', 17)->unique();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};