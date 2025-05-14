<?php
use App\Http\Controllers\FpsCalculatorController;
use Illuminate\Support\Facades\Route;

Route::get('/', [FpsCalculatorController::class, 'index'])->name('fps-calculator');
Route::post('/calculate-fps', [FpsCalculatorController::class, 'calculate'])->name('calculate-fps');

Route::get('/healthz', function () {
    return response()->json(['status' => 'ok'], 200);
});