<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string('external_id')->unique(); 
            $table->string('case_id')->index(); 
            $table->string('filename', 150);
            $table->string('mime_type', 50);
            $table->string('category', 20);
            $table->integer('pages')->unsigned();
            $table->string('uploaded_by')->index(); 
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};