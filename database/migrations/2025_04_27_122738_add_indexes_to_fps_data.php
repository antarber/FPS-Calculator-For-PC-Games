<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('fps_data', function (Blueprint $table) {
            $table->index(
                ['processor_id', 'gpu_id', 'ram_id', 'game_id', 'graphics_setting'],
                'fps_data_combo_idx'
            );
        });
    }

    public function down(): void
    {
        Schema::table('fps_data', function (Blueprint $table) {
            $table->dropIndex('fps_data_combo_idx');
        });
    }
};