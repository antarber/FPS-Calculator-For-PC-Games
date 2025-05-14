<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('fps_data', function (Blueprint $table) {
            $table->string('graphics_setting')->after('game_id');
        });
    }

    public function down(): void
    {
        Schema::table('fps_data', function (Blueprint $table) {
            $table->dropColumn('graphics_setting');
        });
    }
};