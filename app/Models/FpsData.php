<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FpsData extends Model
{
    protected $fillable = ['processor_id', 'gpu_id', 'ram_id', 'game_id', 'graphics_setting', 'fps'];

    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class);
    }

    public function processor(): BelongsTo
    {
        return $this->belongsTo(Processor::class);
    }

    public function gpu(): BelongsTo
    {
        return $this->belongsTo(Gpu::class);
    }

    public function ram(): BelongsTo
    {
        return $this->belongsTo(Ram::class);
    }
}