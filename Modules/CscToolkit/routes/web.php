<?php

use Illuminate\Support\Facades\Route;
use Modules\CscToolkit\Http\Controllers\CscToolkitController;
use Modules\CscToolkit\Http\Controllers\PhotoController;
use Modules\CscToolkit\Http\Controllers\PassportPhotoController;
use Modules\CscToolkit\Http\Controllers\PdfController;
use Modules\CscToolkit\Http\Controllers\OcrController;
use Modules\CscToolkit\Http\Controllers\SignatureController;
use Modules\CscToolkit\Http\Controllers\FormFillerController;
use Modules\CscToolkit\Http\Controllers\PrintController;

Route::middleware(['web'])->group(function () {
    Route::get('/csc/toolkit/photo-processor', [PhotoController::class,         'index'])->name('toolkit.photo');
    Route::get('/csc/toolkit/passport-photo',  [PassportPhotoController::class, 'index'])->name('toolkit.passport');
    Route::get('/csc/toolkit/pdf-creator',     [PdfController::class,           'index'])->name('toolkit.pdf');
    Route::get('/csc/toolkit/ocr-extractor',   [OcrController::class,           'index'])->name('toolkit.ocr');
    Route::get('/csc/toolkit/signature',       [SignatureController::class,     'index'])->name('toolkit.signature');
    Route::get('/csc/toolkit/form-filler',     [FormFillerController::class,    'index'])->name('toolkit.forms');
    Route::get('/csc/toolkit/print-optimizer', [PrintController::class,         'index'])->name('toolkit.print');
});