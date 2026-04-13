<?php

use App\Http\Controllers\Laboratory\LabTestCategoryController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Patient\PatientController;
use App\Http\Controllers\Doctor\DoctorController;
use App\Http\Controllers\Appointment\AppointmentController;
use App\Http\Controllers\Ward\WardController;
use App\Http\Controllers\Medicine\MedicineController;
use App\Http\Controllers\Prescription\PrescriptionController;
use App\Http\Controllers\Dispensing\DispensingController;
use App\Http\Controllers\Laboratory\LabOrderController;
use App\Http\Controllers\Laboratory\LabTestController;
use App\Http\Controllers\Laboratory\LabSampleTypeController;



Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Patients - Full CRUD
Route::resource('patients', PatientController::class);
// Doctors - Full CRUD + availability toggle
Route::resource('doctors', DoctorController::class);
Route::post('doctors/{doctor}/toggle-availability', [DoctorController::class, 'toggleAvailability'])
    ->name('doctors.toggle-availability');

// APPOINTMENT ROUTES
Route::prefix('appointments')->name('appointments.')->group(function () {

    // Calendar view (before resource routes to avoid conflict)
    Route::get('/calendar', [AppointmentController::class, 'calendar'])->name('calendar');

    // Doctor availability check (AJAX)
    Route::get('/availability', [AppointmentController::class, 'availability'])->name('availability');

    // Quick status update (PATCH — called from dropdown in list)
    Route::patch('/{appointment}/status', [AppointmentController::class, 'updateStatus'])->name('status');

    // Standard CRUD
    Route::get('/', [AppointmentController::class, 'index'])->name('index');
    Route::get('/create', [AppointmentController::class, 'create'])->name('create');
    Route::post('/', [AppointmentController::class, 'store'])->name('store');
    Route::get('/{appointment}', [AppointmentController::class, 'show'])->name('show');
    Route::get('/{appointment}/edit', [AppointmentController::class, 'edit'])->name('edit');
    Route::put('/{appointment}', [AppointmentController::class, 'update'])->name('update');
    Route::delete('/{appointment}', [AppointmentController::class, 'destroy'])->name('destroy');

});

// Bed actions
Route::post('wards/assign-bed', [WardController::class, 'assignBed'])->name('wards.assign-bed');
Route::post('wards/beds/{bed}/discharge', [WardController::class, 'discharge'])->name('wards.beds.discharge');
Route::post('wards/beds/{bed}/status', [WardController::class, 'changeBedStatus'])->name('wards.beds.status');

// ✅ Baad mein resource
Route::resource('wards', WardController::class);


// Medicine management routes

Route::prefix('pharmacy')->name('pharmacy.')->group(function () {

    // ── Medicines ──
    Route::post('medicines/{medicine}/add-stock', [MedicineController::class, 'addStock'])
        ->name('medicines.add-stock');

    Route::resource('medicines', MedicineController::class);
    // Medicines
    Route::resource('medicines', MedicineController::class)->names('medicines');
    Route::post('medicines/{medicine}/add-stock', [MedicineController::class, 'addStock'])
        ->name('medicines.add-stock');

    // Prescriptions
    Route::resource('prescriptions', PrescriptionController::class)->except(['edit', 'update'])->names('prescriptions');
    Route::post('prescriptions/{prescription}/cancel', [PrescriptionController::class, 'cancel'])
        ->name('prescriptions.cancel');

    // Dispensing
    Route::resource('dispensings', DispensingController::class)->except(['edit', 'update', 'destroy'])->names('dispensings');
    Route::post('dispensings/{dispensing}/mark-paid', [DispensingController::class, 'markPaid'])
        ->name('dispensings.mark-paid');
});

// Laboratory routes
Route::prefix('lab')->name('lab.')->group(function () {

    // ── Lab Orders ──────────────────────────────────
    Route::get('orders', [LabOrderController::class, 'index'])->name('orders.index');
    Route::get('orders/create', [LabOrderController::class, 'create'])->name('orders.create');
    Route::post('orders', [LabOrderController::class, 'store'])->name('orders.store');
    Route::get('orders/{labOrder}', [LabOrderController::class, 'show'])->name('orders.show');

    Route::post('orders/{labOrder}/collect-sample', [LabOrderController::class, 'collectSample'])->name('orders.collectSample');
    Route::post('orders/{labOrder}/store-results', [LabOrderController::class, 'storeResults'])->name('orders.storeResults');
    Route::post('orders/{labOrder}/record-payment', [LabOrderController::class, 'recordPayment'])->name('orders.recordPayment');
    Route::post('orders/{labOrder}/cancel', [LabOrderController::class, 'cancel'])->name('orders.cancel');
    Route::post('orders/{labOrder}/deliver-report', [LabOrderController::class, 'deliverReport'])->name('orders.deliverReport');

    // ── Results ─────────────────────────────────────
    Route::post('results/{labResult}/verify', [LabOrderController::class, 'verifyResult'])->name('results.verify');

    // ── Lab Tests ───────────────────────────────────
    Route::get('tests', [LabTestController::class, 'index'])->name('tests.index');
    Route::get('tests/create', [LabTestController::class, 'create'])->name('tests.create');
    Route::post('tests', [LabTestController::class, 'store'])->name('tests.store');
    Route::get('tests/{labTest}/edit', [LabTestController::class, 'edit'])->name('tests.edit');
    Route::put('tests/{labTest}', [LabTestController::class, 'update'])->name('tests.update');
    Route::post('tests/{labTest}/toggle', [LabTestController::class, 'toggleActive'])->name('tests.toggleActive');
    Route::get('tests/{labTest}/price', [LabTestController::class, 'getPrice'])->name('tests.price');

    // ── Test Categories (Settings) ───────────────────
    Route::get('categories', [LabTestCategoryController::class, 'index'])->name('categories.index');
    Route::post('categories', [LabTestCategoryController::class, 'store'])->name('categories.store');
    Route::put('categories/{labTestCategory}', [LabTestCategoryController::class, 'update'])->name('categories.update');
    Route::delete('categories/{labTestCategory}', [LabTestCategoryController::class, 'destroy'])->name('categories.destroy');
    Route::post('categories/{labTestCategory}/toggle', [LabTestCategoryController::class, 'toggleActive'])->name('categories.toggleActive');

    // ── Sample Types (Settings) ──────────────────────
    Route::get('sample-types', [LabSampleTypeController::class, 'index'])->name('sample-types.index');
    Route::post('sample-types', [LabSampleTypeController::class, 'store'])->name('sample-types.store');
    Route::put('sample-types/{labSampleType}', [LabSampleTypeController::class, 'update'])->name('sample-types.update');
    Route::delete('sample-types/{labSampleType}', [LabSampleTypeController::class, 'destroy'])->name('sample-types.destroy');
    Route::post('sample-types/{labSampleType}/toggle', [LabSampleTypeController::class, 'toggleActive'])->name('sample-types.toggleActive');
});