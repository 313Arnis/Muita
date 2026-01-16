<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inspections', function (Blueprint $table) {
            $table->id();
            $table->string('external_id')->unique();
            $table->string('case_id')->index();
            
            // NAV ENUM: Izmantojam string
            $table->string('type'); 
            
            $table->string('requested_by');
            // Laiks sagaida formatētu virkni no seeder
            $table->timestamp('start_ts'); 
            
            $table->string('location');
            $table->json('checks')->nullable();
            $table->string('assigned_to')->index(); 
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inspections');
    }
};