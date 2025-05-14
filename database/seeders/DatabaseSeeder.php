<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Processor;
use App\Models\Gpu;
use App\Models\Ram;
use App\Models\Game;
use App\Models\FpsData;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $intelCpus = [
            ['name' => 'Intel Core i9-13900KS', 'performance_score' => 98],
            ['name' => 'Intel Core i9-13900K', 'performance_score' => 96],
            ['name' => 'Intel Core i9-12900K', 'performance_score' => 92],
            ['name' => 'Intel Core i7-13700K', 'performance_score' => 90],
            ['name' => 'Intel Core i7-12700K', 'performance_score' => 88],
            ['name' => 'Intel Core i5-13600K', 'performance_score' => 85],
            ['name' => 'Intel Core i5-12600K', 'performance_score' => 82],
            ['name' => 'Intel Core i5-12400F', 'performance_score' => 78],
            ['name' => 'Intel Core i3-13100', 'performance_score' => 60],
            ['name' => 'Intel Core i9-11900K', 'performance_score' => 84],
            ['name' => 'Intel Core i7-11700K', 'performance_score' => 80],
            ['name' => 'Intel Core i5-11400', 'performance_score' => 75],
        ];
        foreach ($intelCpus as $cpu) {
            Processor::updateOrCreate(
                ['name' => $cpu['name']],
                ['performance_score' => $cpu['performance_score']]
            );
        }

        $amdCpus = [
            ['name' => 'AMD Ryzen 9 7950X3D', 'performance_score' => 97],
            ['name' => 'AMD Ryzen 9 7950X', 'performance_score' => 95],
            ['name' => 'AMD Ryzen 9 7900X3D', 'performance_score' => 93],
            ['name' => 'AMD Ryzen 9 7900X', 'performance_score' => 91],
            ['name' => 'AMD Ryzen 7 7800X3D', 'performance_score' => 89],
            ['name' => 'AMD Ryzen 7 7700X', 'performance_score' => 87],
            ['name' => 'AMD Ryzen 5 7600X', 'performance_score' => 84],
            ['name' => 'AMD Ryzen 9 5950X', 'performance_score' => 86],
            ['name' => 'AMD Ryzen 9 5900X', 'performance_score' => 84],
            ['name' => 'AMD Ryzen 7 5800X3D', 'performance_score' => 82],
            ['name' => 'AMD Ryzen 7 5800X', 'performance_score' => 80],
            ['name' => 'AMD Ryzen 5 5600X', 'performance_score' => 78],
            ['name' => 'AMD Ryzen 5 5500', 'performance_score' => 74],
            ['name' => 'AMD Ryzen 5 3600', 'performance_score' => 65],
            ['name' => 'AMD Ryzen 5 3500', 'performance_score' => 40],
        ];
        foreach ($amdCpus as $cpu) {
            Processor::updateOrCreate(
                ['name' => $cpu['name']],
                ['performance_score' => $cpu['performance_score']]
            );
        }

        $nvidiaGpus = [
            ['name' => 'NVIDIA RTX 4090', 'performance_score' => 100],
            ['name' => 'NVIDIA RTX 4080', 'performance_score' => 95],
            ['name' => 'NVIDIA RTX 4070 Ti', 'performance_score' => 90],
            ['name' => 'NVIDIA RTX 4070', 'performance_score' => 85],
            ['name' => 'NVIDIA RTX 4060 Ti', 'performance_score' => 80],
            ['name' => 'NVIDIA RTX 4060', 'performance_score' => 75],
            ['name' => 'NVIDIA RTX 3090 Ti', 'performance_score' => 92],
            ['name' => 'NVIDIA RTX 3090', 'performance_score' => 90],
            ['name' => 'NVIDIA RTX 3080 Ti', 'performance_score' => 88],
            ['name' => 'NVIDIA RTX 3080', 'performance_score' => 86],
            ['name' => 'NVIDIA RTX 3070 Ti', 'performance_score' => 83],
            ['name' => 'NVIDIA RTX 3070', 'performance_score' => 80],
            ['name' => 'NVIDIA RTX 3060 Ti', 'performance_score' => 78],
            ['name' => 'NVIDIA RTX 3060', 'performance_score' => 75],
            ['name' => 'NVIDIA RTX 2080 Ti', 'performance_score' => 80],
            ['name' => 'NVIDIA RTX 2080 Super', 'performance_score' => 78],
            ['name' => 'NVIDIA RTX 2080', 'performance_score' => 76],
            ['name' => 'NVIDIA RTX 2070 Super', 'performance_score' => 74],
            ['name' => 'NVIDIA RTX 2060 Super', 'performance_score' => 70],
            ['name' => 'NVIDIA RTX 2060', 'performance_score' => 68],
            ['name' => 'NVIDIA GTX 1660 Super', 'performance_score' => 65],
            ['name' => 'NVIDIA GTX 1660', 'performance_score' => 62],
            ['name' => 'NVIDIA GTX 1650', 'performance_score' => 30],
        ];
        foreach ($nvidiaGpus as $gpu) {
            Gpu::updateOrCreate(
                ['name' => $gpu['name']],
                ['performance_score' => $gpu['performance_score']]
            );
        }

        $amdGpus = [
            ['name' => 'AMD RX 7900 XTX', 'performance_score' => 96],
            ['name' => 'AMD RX 7900 XT', 'performance_score' => 92],
            ['name' => 'AMD RX 7800 XT', 'performance_score' => 88],
            ['name' => 'AMD RX 7700 XT', 'performance_score' => 84],
            ['name' => 'AMD RX 7600', 'performance_score' => 78],
            ['name' => 'AMD RX 6950 XT', 'performance_score' => 90],
            ['name' => 'AMD RX 6900 XT', 'performance_score' => 88],
            ['name' => 'AMD RX 6800 XT', 'performance_score' => 86],
            ['name' => 'AMD RX 6800', 'performance_score' => 84],
            ['name' => 'AMD RX 6700 XT', 'performance_score' => 80],
            ['name' => 'AMD RX 6700', 'performance_score' => 78],
            ['name' => 'AMD RX 6600 XT', 'performance_score' => 76],
            ['name' => 'AMD RX 6600', 'performance_score' => 74],
            ['name' => 'AMD RX 5700 XT', 'performance_score' => 70],
            ['name' => 'AMD RX 5700', 'performance_score' => 68],
            ['name' => 'AMD RX 5600 XT', 'performance_score' => 35],
        ];
        foreach ($amdGpus as $gpu) {
            Gpu::updateOrCreate(
                ['name' => $gpu['name']],
                ['performance_score' => $gpu['performance_score']]
            );
        }

        $rams = [
            ['name' => '16GB DDR4 3200MHz', 'performance_score' => 60],
            ['name' => '32GB DDR4 3600MHz', 'performance_score' => 75],
            ['name' => '64GB DDR5 5200MHz', 'performance_score' => 90],
            ['name' => '32GB DDR5 6000MHz', 'performance_score' => 85],
        ];
        foreach ($rams as $ram) {
            Ram::updateOrCreate(
                ['name' => $ram['name']],
                ['performance_score' => $ram['performance_score']]
            );
        }

        $games = [
            ['name' => 'Cyberpunk 2077'],
            ['name' => 'CS 2'],
            ['name' => 'Baldur\'s Gate 3'],
            ['name' => 'Elden Ring'],
            ['name' => 'Fortnite'],
            ['name' => 'The Witcher 3'],
            ['name' => 'GTA V'],
            ['name' => 'Starfield'],
            ['name' => 'Hogwarts Legacy'],
            ['name' => 'Call of Duty: Warzone'],
        ];
        foreach ($games as $game) {
            Game::updateOrCreate(
                ['name' => $game['name']],
                ['name' => $game['name']]
            );
        }

        $graphicsSettings = ['low', 'medium', 'high', 'ultra'];

        $cpus = Processor::orderBy('performance_score', 'desc')->get();
        $gpus = Gpu::orderBy('performance_score', 'desc')->get();
        $rams = Ram::all();
        $games = Game::all();
        foreach ($cpus->take(5) as $cpu) {
            foreach ($gpus->take(5) as $gpu) {
                foreach ($rams as $ram) {
                    foreach ($games as $game) {
                        foreach ($graphicsSettings as $setting) {
                            $difficulty_factor = in_array($game->name, ['Cyberpunk 2077', 'Starfield', 'Hogwarts Legacy']) ? 0.6 : 
                                                (in_array($game->name, ['Elden Ring', 'The Witcher 3', 'Baldur\'s Gate 3']) ? 0.9 : 2.0);
                            $setting_factor = $setting === 'low' ? 1.5 : ($setting === 'medium' ? 1.2 : ($setting === 'high' ? 1.0 : 0.7));
                            $base_fps = ($cpu->performance_score * 0.35 + $gpu->performance_score * 0.6 + $ram->performance_score * 0.05) * $difficulty_factor * $setting_factor;
                            // Учет узких мест
                            $bottleneck_factor = ($cpu->performance_score < 0.7 * $gpu->performance_score) ? 0.8 : 1.0;
                            $fps = $base_fps * $bottleneck_factor;
                            FpsData::updateOrCreate(
                                [
                                    'processor_id' => $cpu->id,
                                    'gpu_id' => $gpu->id,
                                    'ram_id' => $ram->id,
                                    'game_id' => $game->id,
                                    'graphics_setting' => $setting,
                                ],
                                ['fps' => round($fps)]
                            );
                        }
                    }
                }
            }
        }
    }
}