$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

let chart = null;
let chartType = 'bar';
const fpsQuality = {
    excellent: { min: 60, color: 'rgba(34, 197, 94, 0.5)', text: 'Отлично', icon: '✅' },
    normal: { min: 30, color: 'rgba(234, 179, 8, 0.5)', text: 'Нормально', icon: '⚠️' },
    poor: { min: 0, color: 'rgba(239, 68, 68, 0.5)', text: 'Плохо', icon: '❌' }
};

function getFpsQuality(fps) {
    if (fps >= fpsQuality.excellent.min) return fpsQuality.excellent;
    if (fps >= fpsQuality.normal.min) return fpsQuality.normal;
    return fpsQuality.poor;
}

function updateChartColors() {
    if (!chart) return;
    const isDarkMode = document.documentElement.classList.contains('dark');
    const textColor = isDarkMode ? '#ffffff' : '#000000';
    const gridColor = isDarkMode ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.1)';
    chart.options.scales.y.title.color = textColor;
    chart.options.scales.y.ticks.color = textColor;
    chart.options.scales.y.grid.color = gridColor;
    chart.options.scales.x.title.color = textColor;
    chart.options.scales.x.ticks.color = textColor;
    chart.options.plugins.legend.labels.color = textColor;
    chart.update();

    ['processor', 'gpu', 'ram', 'graphics_setting', 'resolution', 'game', 'cpu_brand', 'gpu_brand'].forEach(id => {
        $(`#${id}`).select2('destroy').select2({
            placeholder: id === 'game' ? "Все игры" : "Выбрать",
            allowClear: true,
            width: '100%',
            templateResult: function(data) { return data.text; },
            templateSelection: function(data) { return data.text || (id === 'game' ? "Все игры" : "Выбрать"); }
        });
    });
}

function showErrorModal(message) {
    $('#error-message').text(message);
    $('#error-modal').removeClass('hidden');
}

$(document).ready(function() {
    $('#processor, #gpu, #ram, #graphics_setting, #resolution, #cpu_brand, #gpu_brand').select2({
        placeholder: "Выбрать",
        allowClear: true,
        width: '100%'
    });

    $('#game').select2({
        placeholder: "Все игры",
        allowClear: true,
        width: '100%',
        templateResult: function(data) {
            return data.text;
        },
        templateSelection: function(data) {
            return data.text || "Все игры";
        }
    });

    $('#fps-form')[0].reset();
    $('#processor, #gpu, #ram, #graphics_setting, #resolution, #game, #cpu_brand, #gpu_brand').val('').trigger('change.select2');

    const originalProcessorOptions = [];
    $('#processor option').each(function() {
        originalProcessorOptions.push({
            value: $(this).val(),
            text: $(this).text(),
            brand: $(this).data('brand')
        });
    });

    const originalGpuOptions = [];
    $('#gpu option').each(function() {
        originalGpuOptions.push({
            value: $(this).val(),
            text: $(this).text(),
            brand: $(this).data('brand')
        });
    });

    $('#processor').prop('disabled', true).trigger('change');
    $('#gpu').prop('disabled', true).trigger('change');

    const isDarkMode = document.documentElement.classList.contains('dark');
    const textColor = isDarkMode ? '#ffffff' : '#000000';
    const gridColor = isDarkMode ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.1)';
    chart = new Chart(document.getElementById('fpsChart'), {
        type: chartType,
        data: {
            labels: [],
            datasets: [{
                label: 'FPS',
                data: [],
                backgroundColor: [],
                borderColor: [],
                borderWidth: 1,
                tension: chartType === 'line' ? 0.4 : 0
            }]
        },
        options: {
            animation: {
                duration: 1000,
                easing: 'easeOutQuart'
            },
            scales: {
                y: {
                    beginAtZero: true,
                    title: { display: true, text: 'FPS', color: textColor },
                    grid: { color: gridColor },
                    ticks: { color: textColor }
                },
                x: {
                    title: { display: true, text: 'Игра', color: textColor },
                    grid: { display: false },
                    ticks: { color: textColor }
                }
            },
            plugins: {
                legend: { labels: { color: textColor } }
            }
        }
    });

    $('#cpu_brand').on('change', function() {
        const brand = $(this).val();
        $('#processor').empty().append('<option value="">Выберите процессор</option>');
        originalProcessorOptions.forEach(function(option) {
            if (option.value && (brand === '' || option.brand === brand)) {
                $('#processor').append(`<option value="${option.value}" data-brand="${option.brand}">${option.text}</option>`);
            }
        });
        if (brand) {
            $('#processor').prop('disabled', false);
        } else {
            $('#processor').prop('disabled', true);
        }
        $('#processor').val('').trigger('change');
    });

    $('#gpu_brand').on('change', function() {
        const brand = $(this).val();
        $('#gpu').empty().append('<option value="">Выберите видеокарту</option>');
        originalGpuOptions.forEach(function(option) {
            if (option.value && (brand === '' || option.brand === brand)) {
                $('#gpu').append(`<option value="${option.value}" data-brand="${option.brand}">${option.text}</option>`);
            }
        });
        if (brand) {
            $('#gpu').prop('disabled', false);
        } else {
            $('#gpu').prop('disabled', true);
        }
        $('#gpu').val('').trigger('change');
    });

    $('#processor').on('select2:open', function(e) {
        const cpuBrand = $('#cpu_brand').val();
        if (!cpuBrand) {
            e.preventDefault();
            showErrorModal('Пожалуйста, сначала выберите бренд процессора.');
            $(this).select2('close');
        }
    });

    $('#gpu').on('select2:open', function(e) {
        const gpuBrand = $('#gpu_brand').val();
        if (!gpuBrand) {
            e.preventDefault();
            showErrorModal('Пожалуйста, сначала выберите бренд видеокарты.');
            $(this).select2('close');
        }
    });

    $('.select2-container').on('click', function(e) {
        const selectId = $(this).prev('select').attr('id');
        if (selectId === 'processor' && !$('#cpu_brand').val()) {
            showErrorModal('Пожалуйста, сначала выберите бренд процессора.');
            e.stopPropagation();
        } else if (selectId === 'gpu' && !$('#gpu_brand').val()) {
            showErrorModal('Пожалуйста, сначала выберите бренд видеокарты.');
            e.stopPropagation();
        }
    });

    $('#save-config').on('click', function() {
        const config = {
            processor: $('#processor').val(),
            gpu: $('#gpu').val(),
            ram: $('#ram').val(),
            graphics_setting: $('#graphics_setting').val(),
            resolution: $('#resolution').val(),
            game: $('#game').val()
        };

        const configText = `Конфигурация ПК:\n` +
                          `Процессор: ${$('#processor option:selected').text() || 'Не выбрано'}\n` +
                          `Видеокарта: ${$('#gpu option:selected').text() || 'Не выбрано'}\n` +
                          `ОЗУ: ${$('#ram option:selected').text() || 'Не выбрано'}\n` +
                          `Настройки графики: ${$('#graphics_setting option:selected').text() || 'Не выбрано'}\n` +
                          `Разрешение: ${$('#resolution option:selected').text() || 'Не выбрано'}\n` +
                          `Игра: ${$('#game option:selected').text() || 'Все игры'}`;

        navigator.clipboard.writeText(configText).then(() => {
            showErrorModal('Конфигурация скопирована в буфер обмена!');
        }).catch(() => {
            showErrorModal('Ошибка при копировании конфигурации!');
        });

        localStorage.setItem('savedConfig', JSON.stringify(config));
    });

    $('#toggle-chart-type').on('click', function() {
        chartType = chartType === 'bar' ? 'line' : 'bar';
        $(this).html(chartType === 'bar' ? 
            '<i class="fas fa-chart-line mr-2" aria-hidden="true"></i>Линейный график' : 
            '<i class="fas fa-chart-bar mr-2" aria-hidden="true"></i>Столбчатый график');
        chart.config.type = chartType;
        chart.data.datasets[0].tension = chartType === 'line' ? 0.4 : 0;
        chart.update();
    });

$('#fps-form').on('submit', function(e) {
    e.preventDefault();
    const requiredFields = ['processor', 'gpu', 'ram', 'graphics_setting', 'resolution'];
    let isValid = true;

    requiredFields.forEach(field => {
        if (!$(`#${field}`).val()) {
            isValid = false;
            $(`#${field}`).addClass('invalid-field');
        } else {
            $(`#${field}`).removeClass('invalid-field');
        }
    });

    if (!isValid) {
        showErrorModal('Пожалуйста, заполните все обязательные поля.');
        return;
    }

    $('#submit-loading').removeClass('hidden');

    $.ajax({
        url: calculateFpsRoute, 
        method: 'POST',
        data: $(this).serialize(),
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },

            success: function(data) {
                let tbody = $('#fps-results');
                tbody.empty();
                let labels = [];
                let fpsValues = [];
                let backgroundColors = [];
                let recommendations = [];

                if ($('#game').val() === '') {
                    tbody.append('<tr><td colspan="3" class="p-3 text-center font-semibold">Результаты для всех игр</td></tr>');
                }

                if (data.length > 0) {
                    data.forEach(function(item) {
                        const fps = item.fps;
                        const quality = getFpsQuality(fps);
                        let recommendation = '';
                        if (fps < 30) {
                            recommendation = '<span class="text-xs text-red-500">Попробуйте снизить настройки графики или разрешение.</span>';
                            recommendations.push(recommendation);
                        }
                        tbody.append(`
                            <tr class="hover:bg-gray-100 transition-colors">
                                <td class="p-3">${item.game.name}</td>
                                <td class="p-3" style="color: ${quality.color.replace('0.5', '1')}">${fps} <span class="sr-only">(${quality.text})</span></td>
                                <td class="p-3">${quality.icon} ${quality.text} ${recommendation}</td>
                            </tr>
                        `);
                        labels.push(item.game.name);
                        fpsValues.push(fps);
                        backgroundColors.push(quality.color);
                    });
                } else {
                    tbody.append('<tr><td colspan="3" class="p-3 text-center">Данные не найдены</td></tr>');
                }

                chart.data.labels = labels;
                chart.data.datasets[0].data = fpsValues;
                chart.data.datasets[0].backgroundColor = backgroundColors;
                chart.data.datasets[0].borderColor = backgroundColors.map(c => c.replace('0.5', '1'));
                chart.data.datasets[0].tension = chartType === 'line' ? 0.4 : 0;
                chart.update();

                if (recommendations.length > 0) {
                    showErrorModal('Некоторые игры имеют низкий FPS. Рекомендации отображены в таблице.');
                }
            },
            error: function(xhr) {
                const message = xhr.responseJSON?.message || 'Произошла ошибка при расчете FPS. Пожалуйста, попробуйте снова.';
                showErrorModal(message);
                console.error('AJAX error:', xhr.status, xhr.responseText);
            },
            complete: function() {
                $('#submit-loading').addClass('hidden');
            }
        });
    });

    $('#close-modal').on('click', function() {
        $('#error-modal').addClass('hidden');
    });
});