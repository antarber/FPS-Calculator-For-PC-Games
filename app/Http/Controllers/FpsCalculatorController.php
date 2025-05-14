<?php
namespace App\Http\Controllers;
use App\Models\Processor;
use App\Models\Gpu;
use App\Models\Ram;
use App\Models\Game;
use App\Models\FpsData;
use Illuminate\Http\Request;

class FpsCalculatorController extends Controller
{
    public function index()
    {
        $processors = Processor::orderBy('performance_score', 'desc')->get();
        $gpus = Gpu::orderBy('performance_score', 'desc')->get();
        $rams = Ram::orderBy('performance_score', 'desc')->get();
        $games = Game::orderBy('name')->get();
        return view('fps-calculator', compact('processors', 'gpus', 'rams', 'games'));
    }

    public function calculate(Request $request)
    {
        $request->validate([
            'processor' => 'required|exists:processors,id',
            'gpu' => 'required|exists:gpus,id',
            'ram' => 'required|exists:rams,id',
            'graphics_setting' => 'required|in:low,medium,high,ultra',
            'resolution' => 'required|in:1080p,1440p,4k',
            'game' => 'nullable|exists:games,id',
        ]);

        $processor_id = $request->input('processor');
        $gpu_id = $request->input('gpu');
        $ram_id = $request->input('ram');
        $graphics_setting = $request->input('graphics_setting');
        $resolution = $request->input('resolution');
        $game_id = $request->input('game');

        $query = FpsData::where('processor_id', $processor_id)
            ->where('gpu_id', $gpu_id)
            ->where('ram_id', $ram_id)
            ->where('graphics_setting', $graphics_setting)
            ->with('game');

        if ($game_id) {
            $query->where('game_id', $game_id);
        }

        $fpsData = $query->get();

        if ($fpsData->isEmpty()) {
            $processor = Processor::find($processor_id);
            $gpu = Gpu::find($gpu_id);
            $ram = Ram::find($ram_id);
            $games = $game_id ? Game::where('id', $game_id)->get() : Game::all();
            $fpsData = $games->map(function ($game) use ($processor, $gpu, $ram, $graphics_setting, $resolution) {
                $difficulty_factor = in_array($game->name, ['Cyberpunk 2077', 'Baldur\'s Gate 3', 'Elden Ring', 'The Witcher 3', 'Starfield', 'Hogwarts Legacy']) ? 0.8 : 1.5;
                $setting_factor = $graphics_setting === 'low' ? 1.4 : ($graphics_setting === 'medium' ? 1.2 : ($graphics_setting === 'high' ? 1.0 : 0.8));
                $resolution_factor = $resolution === '1080p' ? 1.0 : ($resolution === '1440p' ? 0.8 : 0.6);
                $fps = ($processor->performance_score * 0.4 + $gpu->performance_score * 0.5 + $ram->performance_score * 0.1) * $difficulty_factor * $setting_factor * $resolution_factor;
                return [
                    'game' => ['name' => $game->name],
                    'fps' => round($fps)
                ];
            });
        }

        return response()->json($fpsData);
    }
}