<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('predictions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('upload_id')->constrained('uploads')->onDelete('cascade');
            
            $table->enum('predicted_label', ['mentah', 'setengah_matang', 'matang']);
            $table->float('probability')->default(0); // contoh: 85.4
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('predictions');
    }
};
