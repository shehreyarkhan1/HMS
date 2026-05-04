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
use App\Http\Controllers\Radiology\RadiologyController;
use App\Http\Controllers\Radiology\RadiologyExamController;
use App\Http\Controllers\Radiology\RadiologyModalityController;
use App\Http\Controllers\Radiology\RadiologyBodyPartController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Employee\EmployeeController;
use App\Http\Controllers\OperationTheater\OtScheduleController;
use App\Http\Controllers\OperationTheater\OtRoomController;
use App\Http\Controllers\BloodBank\BloodBankController;
use App\Http\Controllers\BloodBank\BloodDonorController;
use App\Http\Controllers\BloodBank\BloodDonationController;
use App\Http\Controllers\BloodBank\BloodRequestController;
use App\Http\Controllers\BloodBank\BloodIssueController;
use App\Http\Controllers\BloodBank\BloodCrossmatchController;




Route::prefix('admin')->name('admin.')->group(function () {

    // ── User Management ───────────────────────────────────────────────────
    Route::resource('users', UserController::class);

    // Toggle active/inactive status
    Route::patch('users/{user}/toggle-status', [UserController::class, 'toggleStatus'])
        ->name('users.toggle-status');
});



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


Route::prefix('radiology')->name('radiology.')->group(function () {

    // ══════════════════════════════════════════════════════
    //  Orders
    // ══════════════════════════════════════════════════════
    Route::get('orders', [RadiologyController::class, 'index'])->name('orders.index');
    Route::get('orders/create', [RadiologyController::class, 'create'])->name('orders.create');
    Route::post('orders', [RadiologyController::class, 'store'])->name('orders.store');
    Route::get('orders/{radiologyOrder}', [RadiologyController::class, 'show'])->name('orders.show');

    Route::post('orders/{radiologyOrder}/start-scan', [RadiologyController::class, 'startScan'])->name('orders.startScan');
    Route::post('orders/{radiologyOrder}/complete-scan', [RadiologyController::class, 'completeScan'])->name('orders.completeScan');
    Route::post('orders/{radiologyOrder}/report', [RadiologyController::class, 'storeReport'])->name('orders.storeReport');
    Route::post('orders/{radiologyOrder}/schedule', [RadiologyController::class, 'schedule'])->name('orders.schedule');
    Route::post('orders/{radiologyOrder}/payment', [RadiologyController::class, 'recordPayment'])->name('orders.recordPayment');
    Route::post('orders/{radiologyOrder}/deliver', [RadiologyController::class, 'deliverReport'])->name('orders.deliverReport');
    Route::post('orders/{radiologyOrder}/cancel', [RadiologyController::class, 'cancel'])->name('orders.cancel');

    Route::post('reports/{radiologyReport}/verify', [RadiologyController::class, 'verifyReport'])->name('reports.verify');
    Route::post('reports/{radiologyReport}/amend', [RadiologyController::class, 'amendReport'])->name('reports.amend');

    // ══════════════════════════════════════════════════════
    //  Modalities  ← static, exams se PEHLE
    // ══════════════════════════════════════════════════════
    Route::get('exams/modalities', [RadiologyModalityController::class, 'index'])->name('modalities.index');
    Route::get('exams/modalities/create', [RadiologyModalityController::class, 'create'])->name('modalities.create');
    Route::post('exams/modalities', [RadiologyModalityController::class, 'store'])->name('modalities.store');
    Route::get('exams/modalities/{modality}/edit', [RadiologyModalityController::class, 'edit'])->name('modalities.edit');
    Route::put('exams/modalities/{modality}', [RadiologyModalityController::class, 'update'])->name('modalities.update');
    Route::delete('exams/modalities/{modality}', [RadiologyModalityController::class, 'destroy'])->name('modalities.destroy');
    Route::post('exams/modalities/{modality}/toggle', [RadiologyModalityController::class, 'toggleStatus'])->name('modalities.toggle');

    // ══════════════════════════════════════════════════════
    //  Body Parts  ← static, exams se PEHLE
    // ══════════════════════════════════════════════════════
    Route::get('exams/body-parts', [RadiologyBodyPartController::class, 'index'])->name('body-parts.index');
    Route::get('exams/body-parts/create', [RadiologyBodyPartController::class, 'create'])->name('body-parts.create');
    Route::post('exams/body-parts', [RadiologyBodyPartController::class, 'store'])->name('body-parts.store');
    Route::get('exams/body-parts/{bodyPart}/edit', [RadiologyBodyPartController::class, 'edit'])->name('body-parts.edit');
    Route::put('exams/body-parts/{bodyPart}', [RadiologyBodyPartController::class, 'update'])->name('body-parts.update');
    Route::delete('exams/body-parts/{bodyPart}', [RadiologyBodyPartController::class, 'destroy'])->name('body-parts.destroy');
    Route::post('exams/body-parts/{bodyPart}/toggle', [RadiologyBodyPartController::class, 'toggleStatus'])->name('body-parts.toggle');

    // ══════════════════════════════════════════════════════
    //  Exams  ← dynamic {radiologyExam} SABSE AAKHIR
    // ══════════════════════════════════════════════════════
    Route::get('exams', [RadiologyExamController::class, 'index'])->name('exams.index');
    Route::get('exams/create', [RadiologyExamController::class, 'create'])->name('exams.create');
    Route::post('exams', [RadiologyExamController::class, 'store'])->name('exams.store');
    Route::get('exams/{radiologyExam}', [RadiologyExamController::class, 'show'])->name('exams.show');
    Route::get('exams/{radiologyExam}/edit', [RadiologyExamController::class, 'edit'])->name('exams.edit');
    Route::put('exams/{radiologyExam}', [RadiologyExamController::class, 'update'])->name('exams.update');
    Route::delete('exams/{radiologyExam}', [RadiologyExamController::class, 'destroy'])->name('exams.destroy');
    Route::post('exams/{radiologyExam}/toggle-status', [RadiologyExamController::class, 'toggleStatus'])->name('exams.toggleStatus');
});

// Employee management routes
Route::resource('employees', EmployeeController::class);


// OT Room management routes
Route::prefix('ot')->name('ot.')->group(function () {
    // ── OT SCHEDULES (Resource) ──────────────────────────────────────────
    Route::resource('schedules', OtScheduleController::class)->except(['index'])
        ->parameters(['schedules' => 'ot']);

    // OT index is the main page
    Route::get('/', [OtScheduleController::class, 'index'])->name('index');

    // Quick status update (AJAX)
    Route::patch('schedules/{ot}/status', [OtScheduleController::class, 'updateStatus'])
        ->name('schedules.status');

    // ── OT ROOMS (Master Data) ───────────────────────────────────────────
    Route::prefix('rooms')->name('rooms.')->group(function () {
        Route::get('/', [OtRoomController::class, 'index'])->name('index');
        Route::post('/', [OtRoomController::class, 'store'])->name('store');
        Route::put('/{room}', [OtRoomController::class, 'update'])->name('update');
        Route::delete('/{room}', [OtRoomController::class, 'destroy'])->name('destroy');
    });
});

// Blood Bank routes


Route::prefix('blood-bank')->name('blood-bank.')->group(function () {

    // ── DASHBOARD ────────────────────────────────────────────────────────
    Route::get('/', [BloodBankController::class, 'index'])->name('index');

    // ── DONORS ───────────────────────────────────────────────────────────
    Route::resource('donors', BloodDonorController::class);

    // ── DONATIONS ────────────────────────────────────────────────────────
    Route::get('donations', [BloodDonationController::class, 'index'])->name('donations.index'); 
    Route::post('donations', [BloodDonationController::class, 'store'])->name('donations.store');
    Route::patch('donations/{donation}/screening', [BloodDonationController::class, 'updateScreening'])->name('donations.screening');
    Route::delete('donations/{donation}', [BloodDonationController::class, 'destroy'])->name('donations.destroy');

    // ── BLOOD REQUESTS ───────────────────────────────────────────────────
    Route::get('requests', [BloodRequestController::class, 'index'])->name('requests.index');
    Route::post('requests', [BloodRequestController::class, 'store'])->name('requests.store');
    Route::get('requests/{request}', [BloodRequestController::class, 'show'])->name('requests.show');
    Route::patch('requests/{request}/status', [BloodRequestController::class, 'updateStatus'])->name('requests.status');
    Route::delete('requests/{request}', [BloodRequestController::class, 'destroy'])->name('requests.destroy');

    // ── BLOOD ISSUE (TRANSFUSION) ────────────────────────────────────────
    Route::post('issues', [BloodIssueController::class, 'store'])->name('issues.store');
    Route::patch('issues/{issue}/reaction', [BloodIssueController::class, 'updateReaction'])->name('issues.reaction');

    // ── CROSS-MATCH ───────────────────────────────────────────────────────
    Route::post('crossmatch', [BloodCrossmatchController::class, 'store'])->name('crossmatch.store');
    Route::patch('crossmatch/{crossmatch}/result', [BloodCrossmatchController::class, 'updateResult'])->name('crossmatch.result');
});