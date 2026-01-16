<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cases', function (Blueprint $table) {
            $table->id();
            
            $table->string('external_id')->unique(); 
            $table->string('external_ref')->nullable();
            
            // LABOTS: Aizvietojam ENUM ar string, lai izvairītos no "Data truncated"
            $table->string('status'); 
            $table->string('priority');
            
            $table->timestamp('arrival_ts');
            
            $table->string('checkpoint_id', 20);
            $table->string('origin_country', 2);
            $table->string('destination_country', 2);
            
            $table->json('risk_flags')->nullable();

            $table->string('declarant_id')->index();
            $table->string('consignee_id')->index();
            $table->string('vehicle_id')->index();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cases');
    }
};