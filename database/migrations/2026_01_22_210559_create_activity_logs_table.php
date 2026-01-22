<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('activity_logs', function (Blueprint $table) {
        $table->id();
        $table->string('user_name');
        $table->string('action');      // piem: "CREATED_CASE", "UPDATED_STATUS"
        $table->string('description'); // apraksts cilvēkam saprotamā valodā
        $table->string('ip_address')->nullable();
        $table->json('payload')->nullable(); // vecās/jaunās vērtības
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};