<!DOCTYPE html>
<html lang="ru" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }" x-init="document.documentElement.classList.toggle('dark', darkMode);">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Калькулятор FPS</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="{{ asset('css/fps-calculator.css') }}" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.12.0/dist/cdn.min.js" defer></script>
    <script>
    const calculateFpsRoute = "{{ route('calculate-fps') }}";
</script>
<script src="{{ asset('js/fps-calculator.js') }}"></script>
</head>
<body>
    <div class="container mx-auto p-6 max-w-5xl">
        <div class="flex justify-end mb-4">
            <button @click="darkMode = !darkMode; localStorage.setItem('darkMode', darkMode); document.documentElement.classList.toggle('dark', darkMode); updateChartColors();" 
                    @keyup.enter="darkMode = !darkMode; localStorage.setItem('darkMode', darkMode); document.documentElement.classList.toggle('dark', darkMode); updateChartColors();" 
                    class="p-2 rounded-full bg-gray-200 text-gray-800 hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-300 pulse transition-all"
                    aria-label="Переключить темную тему">
                <i x-show="!darkMode" class="fas fa-moon" aria-hidden="true"></i>
                <i x-show="darkMode" class="fas fa-sun" aria-hidden="true"></i>
            </button>
        </div>

        <h1 class="text-4xl font-extrabold text-center mb-8 bg-gradient-to-r from-blue-500 to-purple-500 text-transparent bg-clip-text animate-pulse">
            Калькулятор FPS
        </h1>

        <form id="fps-form" class="form-container p-8 mb-8 fade-in">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <div>
                    <label for="cpu_brand" class="block text-sm font-medium text-gray-700 tooltip" data-tooltip="Фильтр процессоров по бренду">
                        <i class="fas fa-filter mr-2" aria-hidden="true"></i>Бренд CPU
                    </label>
                    <select id="cpu_brand" class="mt-1 block w-full p-2 border rounded-md" aria-describedby="cpu_brand_help">
                        <option value="">Все бренды</option>
                        <option value="Intel">Intel</option>
                        <option value="AMD">AMD</option>
                    </select>
                    <span id="cpu_brand_help" class="sr-only">Выберите бренд процессора для фильтрации доступных моделей</span>
                </div>
                <div>
                    <label for="processor" class="block text-sm font-medium text-gray-700 tooltip" data-tooltip="Выберите процессор для расчёта FPS">
                        <i class="fas fa-microchip mr-2" aria-hidden="true"></i>Процессор
                    </label>
                    <select id="processor" name="processor" class="mt-1 block w-full p-2 border rounded-md" required aria-describedby="processor_help">
                        <option value="">Выберите процессор</option>
                        @foreach($processors as $processor)
                            <option value="{{ $processor->id }}" data-brand="{{ strpos($processor->name, 'Intel') !== false ? 'Intel' : 'AMD' }}">
                                {{ $processor->name }}
                            </option>
                        @endforeach
                    </select>
                    <span id="processor_help" class="sr-only">Выберите модель процессора для расчета FPS</span>
                </div>
                <div>
                    <label for="gpu_brand" class="block text-sm font-medium text-gray-700 tooltip" data-tooltip="Фильтр видеокарт по бренду">
                        <i class="fas fa-filter mr-2" aria-hidden="true"></i>Бренд GPU
                    </label>
                    <select id="gpu_brand" class="mt-1 block w-full p-2 border rounded-md" aria-describedby="gpu_brand_help">
                        <option value="">Все бренды</option>
                        <option value="NVIDIA">NVIDIA</option>
                        <option value="AMD">AMD</option>
                    </select>
                    <span id="gpu_brand_help" class="sr-only">Выберите бренд видеокарты для фильтрации доступных моделей</span>
                </div>
                <div>
                    <label for="gpu" class="block text-sm font-medium text-gray-700 tooltip" data-tooltip="Выберите видеокарту для расчёта FPS">
                        <i class="fas fa-desktop mr-2" aria-hidden="true"></i>Видеокарта
                    </label>
                    <select id="gpu" name="gpu" class="mt-1 block w-full p-2 border rounded-md" required aria-describedby="gpu_help">
                        <option value="">Выберите видеокарту</option>
                        @foreach($gpus as $gpu)
                            <option value="{{ $gpu->id }}" data-brand="{{ strpos($gpu->name, 'NVIDIA') !== false ? 'NVIDIA' : 'AMD' }}">
                                {{ $gpu->name }}
                            </option>
                        @endforeach
                    </select>
                    <span id="gpu_help" class="sr-only">Выберите модель видеокарты для расчета FPS</span>
                </div>
                <div>
                    <label for="ram" class="block text-sm font-medium text-gray-700 tooltip" data-tooltip="Выберите объем ОЗУ">
                        <i class="fas fa-memory mr-2" aria-hidden="true"></i>ОЗУ
                    </label>
                    <select id="ram" name="ram" class="mt-1 block w-full p-2 border rounded-md" required aria-describedby="ram_help">
                        <option value="">Выберите ОЗУ</option>
                        @foreach($rams as $ram)
                            <option value="{{ $ram->id }}">{{ $ram->name }}</option>
                        @endforeach
                    </select>
                    <span id="ram_help" class="sr-only">Выберите объем оперативной памяти для расчета FPS</span>
                </div>
                <div>
                    <label for="graphics_setting" class="block text-sm font-medium text-gray-700 tooltip" data-tooltip="Уровень графических настроек">
                        <i class="fas fa-cog mr-2" aria-hidden="true"></i>Настройки графики
                    </label>
                    <select id="graphics_setting" name="graphics_setting" class="mt-1 block w-full p-2 border rounded-md" required aria-describedby="graphics_setting_help">
                        <option value="low">Слабые</option>
                        <option value="medium">Средние</option>
                        <option value="high">Высокие</option>
                        <option value="ultra">Ультра</option>
                    </select>
                    <span id="graphics_setting_help" class="sr-only">Выберите уровень графических настроек для расчета FPS</span>
                </div>
                <div>
                    <label for="resolution" class="block text-sm font-medium text-gray-700 tooltip" data-tooltip="Разрешение экрана">
                        <i class="fas fa-tv mr-2" aria-hidden="true"></i>Разрешение
                    </label>
                    <select id="resolution" name="resolution" class="mt-1 block w-full p-2 border rounded-md" required aria-describedby="resolution_help">
                        <option value="1080p">1920x1080</option>
                        <option value="1440p">2560x1440</option>
                        <option value="4k">3840x2160</option>
                    </select>
                    <span id="resolution_help" class="sr-only">Выберите разрешение экрана для расчета FPS</span>
                </div>
                <div>
                    <label for="game" class="block text-sm font-medium text-gray-700 tooltip" data-tooltip="Выберите игру или все игры">
                        <i class="fas fa-gamepad mr-2" aria-hidden="true"></i>Игра
                    </label>
                    <select id="game" name="game" class="mt-1 block w-full p-2 border rounded-md" aria-describedby="game_help">
                        <option value="">Все игры</option>
                        @foreach($games as $game)
                            <option value="{{ $game->id }}">{{ $game->name }}</option>
                        @endforeach
                    </select>
                    <span id="game_help" class="sr-only">Выберите игру для расчета FPS или оставьте пустым для всех игр</span>
                </div>
            </div>
            <div class="mt-6 flex space-x-4">
                <button type="submit" class="flex-1 bg-gradient-to-r from-blue-600 to-purple-600 text-white p-3 rounded-md hover:from-blue-700 hover:to-purple-700 pulse transition-all" aria-label="Рассчитать FPS">
                    <i class="fas fa-calculator mr-2" aria-hidden="true"></i>Рассчитать FPS
                    <span id="submit-loading" class="hidden ml-2 animate-spin inline-block h-4 w-4 border-t-2 border-white rounded-full"></span>
                </button>
                <button type="button" id="save-config" class="flex-1 bg-green-600 text-white p-3 rounded-md hover:bg-green-700 pulse transition-all" aria-label="Сохранить конфигурацию">
                    <i class="fas fa-copy mr-2" aria-hidden="true"></i>Сохранить конфигурацию
                </button>
            </div>
        </form>

        <div id="error-modal" class="hidden fixed inset-0 modal flex items-center justify-center z-50">
            <div class="modal-content p-6 max-w-md w-full">
                <h3 class="text-lg font-semibold mb-4">Уведомление</h3>
                <p id="error-message" class="mb-4"></p>
                <button id="close-modal" class="bg-blue-600 text-white p-2 rounded-md hover:bg-blue-700 pulse transition-all">Хорошо</button>
            </div>
        </div>

        <h2 class="text-3xl font-semibold mb-6 text-gray-800 fade-in">Результаты (Приблизительный FPS)</h2>
        <div class="form-container p-8 fade-in">
            <table class="w-full table-auto">
                <thead>
                    <tr class="bg-gradient-to-r from-blue-600 to-purple-600 text-white">
                        <th class="p-3 text-left">Игра</th>
                        <th class="p-3 text-left">FPS</th>
                        <th class="p-3 text-left">Качество</th>
                    </tr>
                </thead>
                <tbody id="fps-results"></tbody>
            </table>
            <div class="mt-4 flex justify-end">
                <button id="toggle-chart-type" class="bg-gray-200 text-gray-800 p-2 rounded-md hover:bg-gray-300 pulse transition-all dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-300" aria-label="Переключить тип графика">
                    <i class="fas fa-chart-line mr-2" aria-hidden="true"></i>Линейный график
                </button>
            </div>
            <canvas id="fpsChart" class="mt-4"></canvas>
        </div>

        <footer class="fade-in">
            <p>Server part by Ruben Voskanyan</p>
            <p>Design/animation by Igorek Reactcoder</p>
        </footer>
    </div>
</body>
</html>