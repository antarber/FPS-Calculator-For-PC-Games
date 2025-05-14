<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('fps_data', function (Blueprint $table) {
            $table->id();
            $table->foreignId('processor_id')->constrained()->onDelete('cascade');
            $table->foreignId('gpu_id')->constrained()->onDelete('cascade');
            $table->foreignId('ram_id')->constrained()->onDelete('cascade');
            $table->foreignId('game_id')->constrained()->onDelete('cascade');
            $table->integer('fps');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fps_data');
    }
};
