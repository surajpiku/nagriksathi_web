<?php

use Illuminate\Support\Facades\Route;
use Modules\CscToolkit\Http\Controllers\PhotoController;
use Modules\CscToolkit\Http\Controllers\PassportPhotoController;
use Modules\CscToolkit\Http\Controllers\PdfController;
use Modules\CscToolkit\Http\Controllers\OcrController;
use Modules\CscToolkit\Http\Controllers\SignatureController;
use Modules\CscToolkit\Http\Controllers\FormFillerController;
use Modules\CscToolkit\Http\Controllers\PrintController;

Route::middleware(['auth:sanctum', 'role:csc_agent|admin'])
    ->prefix('v1/csc/toolkit')
    ->group(function () {

        // Tool 1 — Photo Processor
        Route::post('/photo/process',        [PhotoController::class, 'process']);
        Route::post('/photo/save',           [PhotoController::class, 'save']);

        // Tool 2 — Passport Photo
        Route::get('/photo/presets',         [PassportPhotoController::class, 'presets']);
        Route::post('/photo/passport',       [PassportPhotoController::class, 'generate']);
        Route::post('/photo/remove-bg',      [PassportPhotoController::class, 'removeBackground']);

        // Tool 3 — PDF Creator
        Route::post('/pdf/merge',            [PdfController::class, 'merge']);
        Route::post('/pdf/compress',         [PdfController::class, 'compress']);

        // Tool 4 — OCR
        Route::post('/ocr/extract',          [OcrController::class, 'extract']);

        // Tool 5 — Signature
        Route::post('/signature/add',        [SignatureController::class, 'add']);
        Route::get('/signature/stamps',      [SignatureController::class, 'stamps']);

        // Tool 6 — Form Filler
        Route::get('/forms',                 [FormFillerController::class, 'index']);
        Route::get('/forms/{id}',            [FormFillerController::class, 'show']);
        Route::post('/forms/{id}/autofill',  [FormFillerController::class, 'autofill']);

        // Tool 7 — Print Optimizer
        Route::post('/print/layout',         [PrintController::class, 'layout']);
    });
