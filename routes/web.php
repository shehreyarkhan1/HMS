<?php

use App\Http\Controllers\Appointment\AppointmentController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Billing\BillingController;
use App\Http\Controllers\Billing\BillServiceChargeController;
use App\Http\Controllers\BloodBank\BloodBankController;
use App\Http\Controllers\BloodBank\BloodCrossmatchController;
use App\Http\Controllers\BloodBank\BloodDonationController;
use App\Http\Controllers\BloodBank\BloodDonorController;
use App\Http\Controllers\BloodBank\BloodIssueController;
use App\Http\Controllers\BloodBank\BloodRequestController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Death\BodyReleaseController;
use App\Http\Controllers\Death\DeathCertificateController;
use App\Http\Controllers\Death\MortuaryController;
use App\Http\Controllers\Dispensing\DispensingController;
use App\Http\Controllers\Doctor\DoctorController;
use App\Http\Controllers\DoctorDashboard\DoctorDashboardController;
use App\Http\Controllers\Employee\EmployeeController;
use App\Http\Controllers\HR\AttendanceController;
use App\Http\Controllers\HR\DisciplinaryController;
use App\Http\Controllers\HR\HolidayController;
use App\Http\Controllers\HR\LeaveController;
use App\Http\Controllers\HR\LeaveTypeController;
use App\Http\Controllers\HR\PayrollController;
use App\Http\Controllers\HR\SalaryController;
use App\Http\Controllers\Laboratory\LabOrderController;
use App\Http\Controllers\Laboratory\LabSampleTypeController;
use App\Http\Controllers\Laboratory\LabTestCategoryController;
use App\Http\Controllers\Laboratory\LabTestController;
use App\Http\Controllers\Medicine\MedicineController;
use App\Http\Controllers\OperationTheater\OtRoomController;
use App\Http\Controllers\OperationTheater\OtScheduleController;
use App\Http\Controllers\Patient\PatientController;
use App\Http\Controllers\PatientReport\PatientReportController;
use App\Http\Controllers\Prescription\PrescriptionController;
use App\Http\Controllers\Radiology\RadiologyBodyPartController;
use App\Http\Controllers\Radiology\RadiologyController;
use App\Http\Controllers\Radiology\RadiologyExamController;
use App\Http\Controllers\Radiology\RadiologyModalityController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Ward\WardController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// ══════════════════════════════════════════════════════════════════════════════
//  AUTH ROUTES — No middleware (guest only)
// ══════════════════════════════════════════════════════════════════════════════
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

Route::post('/logout', [LoginController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// ══════════════════════════════════════════════════════════════════════════════
//  PROTECTED ROUTES — Auth + Active check (EnsureActive runs via web group)
// ══════════════════════════════════════════════════════════════════════════════
Route::middleware(['auth'])->group(function () {

    // ── DASHBOARD ─────────────────────────────────────────────────────────
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index']);

    // ── SHARED AJAX ── (accessible by all authenticated roles) ────────────
    Route::get('/ajax/patient-search', [BillingController::class, 'patientSearch'])
        ->name('ajax.patient-search');

    // ── USER MANAGEMENT (super_admin only) ────────────────────────────────
    Route::middleware('role:super_admin')
        ->prefix('admin')->name('admin.')
        ->group(function () {
            Route::resource('users', UserController::class);
            Route::patch('users/{user}/toggle-status', [UserController::class, 'toggleStatus'])
                ->name('users.toggle-status');
        });

    // ── PATIENTS ──────────────────────────────────────────────────────────
    Route::middleware('role:super_admin,receptionist,doctor,nurse')
        ->group(function () {
            Route::resource('patients', PatientController::class);
        });

    // ── DOCTORS ───────────────────────────────────────────────────────────
    Route::middleware('role:super_admin,receptionist,hr_manager')
        ->group(function () {
            Route::resource('doctors', DoctorController::class);
            Route::post('doctors/{doctor}/toggle-availability',
                [DoctorController::class, 'toggleAvailability'])
                ->name('doctors.toggle-availability');
        });

    // ── APPOINTMENTS ──────────────────────────────────────────────────────
    Route::middleware('role:super_admin,receptionist,doctor,nurse')
        ->prefix('appointments')->name('appointments.')
        ->group(function () {
            Route::get('/calendar', [AppointmentController::class, 'calendar'])->name('calendar');
            Route::get('/availability', [AppointmentController::class, 'availability'])->name('availability');
            Route::patch('/{appointment}/status', [AppointmentController::class, 'updateStatus'])->name('status');
            Route::get('/', [AppointmentController::class, 'index'])->name('index');
            Route::get('/create', [AppointmentController::class, 'create'])->name('create');
            Route::post('/', [AppointmentController::class, 'store'])->name('store');
            Route::get('/{appointment}', [AppointmentController::class, 'show'])->name('show');
            Route::get('/{appointment}/edit', [AppointmentController::class, 'edit'])->name('edit');
            Route::put('/{appointment}', [AppointmentController::class, 'update'])->name('update');
            Route::delete('/{appointment}', [AppointmentController::class, 'destroy'])->name('destroy');
        });

    // ── WARDS ─────────────────────────────────────────────────────────────
    Route::middleware('role:super_admin,doctor,nurse,receptionist')
        ->group(function () {
            Route::post('wards/assign-bed', [WardController::class, 'assignBed'])->name('wards.assign-bed');
            Route::post('wards/beds/{bed}/discharge', [WardController::class, 'discharge'])->name('wards.beds.discharge');
            Route::post('wards/beds/{bed}/status', [WardController::class, 'changeBedStatus'])->name('wards.beds.status');
            Route::resource('wards', WardController::class);
        });

    // ── PHARMACY ──────────────────────────────────────────────────────────
    // Medicines — pharmacist + doctor dono
    Route::middleware('role:super_admin,pharmacist')
        ->prefix('pharmacy')->name('pharmacy.')
        ->group(function () {
            Route::post('medicines/{medicine}/add-stock', [MedicineController::class, 'addStock'])
                ->name('medicines.add-stock');
            Route::resource('medicines', MedicineController::class)->names('medicines');
        });

    // Prescription CREATE/STORE — sirf doctor
    Route::middleware('role:super_admin,doctor')
        ->prefix('pharmacy')->name('pharmacy.')
        ->group(function () {
            Route::get('prescriptions/create', [PrescriptionController::class, 'create'])->name('prescriptions.create');
            Route::post('prescriptions', [PrescriptionController::class, 'store'])->name('prescriptions.store');
        });

    // Prescription INDEX/SHOW/CANCEL — pharmacist + doctor dono
    Route::middleware('role:super_admin,pharmacist,doctor')
        ->prefix('pharmacy')->name('pharmacy.')
        ->group(function () {
            Route::get('prescriptions', [PrescriptionController::class, 'index'])->name('prescriptions.index');
            Route::get('prescriptions/{prescription}', [PrescriptionController::class, 'show'])->name('prescriptions.show');
            Route::post('prescriptions/{prescription}/cancel', [PrescriptionController::class, 'cancel'])->name('prescriptions.cancel');
        });

    // Dispensing — sirf pharmacist
    Route::middleware('role:super_admin,pharmacist')
        ->prefix('pharmacy')->name('pharmacy.')
        ->group(function () {
            Route::resource('dispensings', DispensingController::class)
                ->except(['edit', 'update', 'destroy'])->names('dispensings');
        });

    // ── LABORATORY ────────────────────────────────────────────────────────

    // Doctor — create, store, aur show
    Route::middleware('role:super_admin,lab_technician,doctor')
        ->prefix('lab')->name('lab.')
        ->group(function () {
            Route::get('orders/create', [LabOrderController::class, 'create'])->name('orders.create');
            Route::post('orders', [LabOrderController::class, 'store'])->name('orders.store');
            Route::get('orders/{labOrder}', [LabOrderController::class, 'show'])->name('orders.show');
        });

    // Baaki sab sirf super_admin aur lab_technician ke liye
    Route::middleware('role:super_admin,lab_technician')
        ->prefix('lab')->name('lab.')
        ->group(function () {
            // Orders
            Route::get('orders', [LabOrderController::class, 'index'])->name('orders.index');
            Route::post('orders/{labOrder}/collect-sample', [LabOrderController::class, 'collectSample'])->name('orders.collectSample');
            Route::post('orders/{labOrder}/store-results', [LabOrderController::class, 'storeResults'])->name('orders.storeResults');
            Route::post('orders/{labOrder}/record-payment', [LabOrderController::class, 'recordPayment'])->name('orders.recordPayment');
            Route::post('orders/{labOrder}/cancel', [LabOrderController::class, 'cancel'])->name('orders.cancel');
            Route::post('orders/{labOrder}/deliver-report', [LabOrderController::class, 'deliverReport'])->name('orders.deliverReport');
            // Results
            Route::post('results/{labResult}/verify', [LabOrderController::class, 'verifyResult'])->name('results.verify');
            // Tests
            Route::get('tests', [LabTestController::class, 'index'])->name('tests.index');
            Route::get('tests/create', [LabTestController::class, 'create'])->name('tests.create');
            Route::post('tests', [LabTestController::class, 'store'])->name('tests.store');
            Route::get('tests/{labTest}/edit', [LabTestController::class, 'edit'])->name('tests.edit');
            Route::put('tests/{labTest}', [LabTestController::class, 'update'])->name('tests.update');
            Route::post('tests/{labTest}/toggle', [LabTestController::class, 'toggleActive'])->name('tests.toggleActive');
            Route::get('tests/{labTest}/price', [LabTestController::class, 'getPrice'])->name('tests.price');
            // Categories
            Route::get('categories', [LabTestCategoryController::class, 'index'])->name('categories.index');
            Route::post('categories', [LabTestCategoryController::class, 'store'])->name('categories.store');
            Route::put('categories/{labTestCategory}', [LabTestCategoryController::class, 'update'])->name('categories.update');
            Route::delete('categories/{labTestCategory}', [LabTestCategoryController::class, 'destroy'])->name('categories.destroy');
            Route::post('categories/{labTestCategory}/toggle', [LabTestCategoryController::class, 'toggleActive'])->name('categories.toggleActive');
            // Sample Types
            Route::get('sample-types', [LabSampleTypeController::class, 'index'])->name('sample-types.index');
            Route::post('sample-types', [LabSampleTypeController::class, 'store'])->name('sample-types.store');
            Route::put('sample-types/{labSampleType}', [LabSampleTypeController::class, 'update'])->name('sample-types.update');
            Route::delete('sample-types/{labSampleType}', [LabSampleTypeController::class, 'destroy'])->name('sample-types.destroy');
            Route::post('sample-types/{labSampleType}/toggle', [LabSampleTypeController::class, 'toggleActive'])->name('sample-types.toggleActive');
        });

    // ── RADIOLOGY ─────────────────────────────────────────────────────────
    Route::middleware('role:super_admin,radiologist,doctor')
        ->prefix('radiology')->name('radiology.')
        ->group(function () {
            // Orders
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
            // Modalities (static — pehle)
            Route::get('exams/modalities', [RadiologyModalityController::class, 'index'])->name('modalities.index');
            Route::get('exams/modalities/create', [RadiologyModalityController::class, 'create'])->name('modalities.create');
            Route::post('exams/modalities', [RadiologyModalityController::class, 'store'])->name('modalities.store');
            Route::get('exams/modalities/{modality}/edit', [RadiologyModalityController::class, 'edit'])->name('modalities.edit');
            Route::put('exams/modalities/{modality}', [RadiologyModalityController::class, 'update'])->name('modalities.update');
            Route::delete('exams/modalities/{modality}', [RadiologyModalityController::class, 'destroy'])->name('modalities.destroy');
            Route::post('exams/modalities/{modality}/toggle', [RadiologyModalityController::class, 'toggleStatus'])->name('modalities.toggle');
            // Body Parts (static — pehle)
            Route::get('exams/body-parts', [RadiologyBodyPartController::class, 'index'])->name('body-parts.index');
            Route::get('exams/body-parts/create', [RadiologyBodyPartController::class, 'create'])->name('body-parts.create');
            Route::post('exams/body-parts', [RadiologyBodyPartController::class, 'store'])->name('body-parts.store');
            Route::get('exams/body-parts/{bodyPart}/edit', [RadiologyBodyPartController::class, 'edit'])->name('body-parts.edit');
            Route::put('exams/body-parts/{bodyPart}', [RadiologyBodyPartController::class, 'update'])->name('body-parts.update');
            Route::delete('exams/body-parts/{bodyPart}', [RadiologyBodyPartController::class, 'destroy'])->name('body-parts.destroy');
            Route::post('exams/body-parts/{bodyPart}/toggle', [RadiologyBodyPartController::class, 'toggleStatus'])->name('body-parts.toggle');
            // Exams (dynamic — aakhir mein)
            Route::get('exams', [RadiologyExamController::class, 'index'])->name('exams.index');
            Route::get('exams/create', [RadiologyExamController::class, 'create'])->name('exams.create');
            Route::post('exams', [RadiologyExamController::class, 'store'])->name('exams.store');
            Route::get('exams/{radiologyExam}', [RadiologyExamController::class, 'show'])->name('exams.show');
            Route::get('exams/{radiologyExam}/edit', [RadiologyExamController::class, 'edit'])->name('exams.edit');
            Route::put('exams/{radiologyExam}', [RadiologyExamController::class, 'update'])->name('exams.update');
            Route::delete('exams/{radiologyExam}', [RadiologyExamController::class, 'destroy'])->name('exams.destroy');
            Route::post('exams/{radiologyExam}/toggle-status', [RadiologyExamController::class, 'toggleStatus'])->name('exams.toggleStatus');
        });

    // ── EMPLOYEES ─────────────────────────────────────────────────────────
    Route::middleware('role:super_admin,hr_manager')
        ->group(function () {
            Route::resource('employees', EmployeeController::class);
        });

    // ── OPERATION THEATER ─────────────────────────────────────────────────
    Route::middleware('role:super_admin,doctor,nurse')
        ->prefix('ot')->name('ot.')
        ->group(function () {
            Route::resource('schedules', OtScheduleController::class)
                ->except(['index', 'destroy'])->parameters(['schedules' => 'ot']);
            Route::get('/', [OtScheduleController::class, 'index'])->name('index');
            Route::patch('schedules/{ot}/status', [OtScheduleController::class, 'updateStatus'])
                ->name('schedules.status');
            Route::prefix('rooms')->name('rooms.')->group(function () {
                Route::get('/', [OtRoomController::class, 'index'])->name('index');
                Route::post('/', [OtRoomController::class, 'store'])->name('store');
                Route::put('/{room}', [OtRoomController::class, 'update'])->name('update');
                Route::delete('/{room}', [OtRoomController::class, 'destroy'])->name('destroy');
            });
        });

    // OT Schedule delete — sirf super_admin
    Route::middleware('role:super_admin')
        ->prefix('ot')->name('ot.')
        ->group(function () {
            Route::delete('schedules/{ot}', [OtScheduleController::class, 'destroy'])
                ->name('schedules.destroy');
        });

    // ── BLOOD BANK (super_admin only) ─────────────────────────────────────
    Route::middleware('role:super_admin')
        ->prefix('blood-bank')->name('blood-bank.')
        ->group(function () {
            Route::get('/', [BloodBankController::class, 'index'])->name('index');
            Route::resource('donors', BloodDonorController::class);
            Route::get('donations', [BloodDonationController::class, 'index'])->name('donations.index');
            Route::post('donations', [BloodDonationController::class, 'store'])->name('donations.store');
            Route::patch('donations/{donation}/screening', [BloodDonationController::class, 'updateScreening'])->name('donations.screening');
            Route::delete('donations/{donation}', [BloodDonationController::class, 'destroy'])->name('donations.destroy');
            Route::get('requests', [BloodRequestController::class, 'index'])->name('requests.index');
            Route::post('requests', [BloodRequestController::class, 'store'])->name('requests.store');
            Route::get('requests/{request}', [BloodRequestController::class, 'show'])->name('requests.show');
            Route::patch('requests/{request}/status', [BloodRequestController::class, 'updateStatus'])->name('requests.status');
            Route::delete('requests/{bloodRequest}', [BloodRequestController::class, 'destroy'])->name('requests.destroy');
            Route::post('issues', [BloodIssueController::class, 'store'])->name('issues.store');
            Route::patch('issues/{issue}/reaction', [BloodIssueController::class, 'updateReaction'])->name('issues.reaction');
            Route::post('crossmatch', [BloodCrossmatchController::class, 'store'])->name('crossmatch.store');
            Route::patch('crossmatch/{crossmatch}/result', [BloodCrossmatchController::class, 'updateResult'])->name('crossmatch.result');
        });

    // ── BILLING ───────────────────────────────────────────────────────────
    Route::middleware('role:super_admin,accountant,receptionist')
        ->prefix('billing')->name('billing.')
        ->group(function () {
            Route::get('/', [BillingController::class, 'index'])->name('index');
            Route::get('/create', [BillingController::class, 'create'])->name('create');
            Route::post('/', [BillingController::class, 'store'])->name('store');
            // Patient search is shared — defined globally above (accessible to all roles)
            Route::get('/patient/{patientId}/pending-services', [BillingController::class, 'pendingServices'])->name('pending-services');
            Route::prefix('service-charges')->name('service-charges.')->group(function () {
                Route::get('/', [BillServiceChargeController::class, 'index'])->name('index');
                Route::get('/create', [BillServiceChargeController::class, 'create'])->name('create');
                Route::post('/', [BillServiceChargeController::class, 'store'])->name('store');
                Route::get('/{charge}/edit', [BillServiceChargeController::class, 'edit'])->name('edit');
                Route::put('/{charge}', [BillServiceChargeController::class, 'update'])->name('update');
                Route::delete('/{charge}', [BillServiceChargeController::class, 'destroy'])->name('destroy');
                Route::patch('/{charge}/toggle', [BillServiceChargeController::class, 'toggle'])->name('toggle');
            });
            Route::get('/{bill}', [BillingController::class, 'show'])->whereNumber('bill')->name('show');
            Route::get('/{bill}/edit', [BillingController::class, 'edit'])->whereNumber('bill')->name('edit');
            Route::put('/{bill}', [BillingController::class, 'update'])->whereNumber('bill')->name('update');
            Route::post('/{bill}/finalize', [BillingController::class, 'finalize'])->whereNumber('bill')->name('finalize');
            Route::post('/{bill}/payment', [BillingController::class, 'addPayment'])->whereNumber('bill')->name('payment');
            Route::patch('/{bill}/cancel', [BillingController::class, 'cancel'])->whereNumber('bill')->name('cancel');
            Route::get('/{bill}/print', [BillingController::class, 'printInvoice'])->whereNumber('bill')->name('print');
        });

    // ── MORTUARY (super_admin only) ───────────────────────────────────────
    Route::middleware('role:super_admin')
        ->prefix('mortuary')->name('mortuary.')
        ->group(function () {
            Route::get('/', [MortuaryController::class, 'index'])->name('index');
            Route::get('/create', [MortuaryController::class, 'create'])->name('create');
            Route::post('/', [MortuaryController::class, 'store'])->name('store');
            Route::get('/{mortuary}', [MortuaryController::class, 'show'])->name('show');
            Route::get('/{mortuary}/edit', [MortuaryController::class, 'edit'])->name('edit');
            Route::put('/{mortuary}', [MortuaryController::class, 'update'])->name('update');
            Route::delete('/{mortuary}', [MortuaryController::class, 'destroy'])->name('destroy');
            Route::patch('/{mortuary}/status', [MortuaryController::class, 'updateStatus'])->name('status');
            Route::get('/{mortuary}/certificates/create', [DeathCertificateController::class, 'create'])->name('certificates.create');
            Route::post('/{mortuary}/certificates', [DeathCertificateController::class, 'store'])->name('certificates.store');
            Route::get('/certificates/{certificate}/print', [DeathCertificateController::class, 'print'])->name('certificates.print');
            Route::post('/certificates/{certificate}/verify', [DeathCertificateController::class, 'verify'])->name('certificates.verify');
            Route::delete('/certificates/{certificate}', [DeathCertificateController::class, 'destroy'])->name('certificates.destroy');
            Route::get('/{mortuary}/release/create', [BodyReleaseController::class, 'create'])->name('release.create');
            Route::post('/{mortuary}/release', [BodyReleaseController::class, 'store'])->name('release.store');
        });

    // ── REPORTS ───────────────────────────────────────────────────────────
    Route::middleware('role:super_admin,accountant,hr_manager,doctor')
        ->prefix('reports')->name('reports.')
        ->group(function () {
            Route::get('/patients', [PatientReportController::class, 'index'])->name('patients.index');
            Route::get('/patients/{patient}', [PatientReportController::class, 'show'])->name('patients.show');
        });

    // ── DOCTOR DASHBOARD ──────────────────────────────────────────────────
    Route::middleware('role:super_admin,doctor')
        ->prefix('doctor')->name('doctor.')
        ->group(function () {
            Route::get('/dashboard', [DoctorDashboardController::class, 'index'])
                ->name('dashboard');
        });

    // ROUTES FOR HR
    Route::middleware('role:super_admin,hr_manager')->prefix('hr')->name('hr.')->group(function () {
        // Leave types setting
        Route::prefix('leave-types')->name('leave-types.')->group(function () {
            Route::get('/', [LeaveTypeController::class, 'index'])->name('index');
            Route::post('/', [LeaveTypeController::class, 'store'])->name('store');
            Route::put('/{leaveType}', [LeaveTypeController::class, 'update'])->name('update');
            Route::delete('/{leaveType}', [LeaveTypeController::class, 'destroy'])->name('destroy');
            Route::post('/{leaveType}/toggle', [LeaveTypeController::class, 'toggle'])->name('toggle');

        });
        // ── Leave Requests ─────────────────────────────────────────
        Route::prefix('leaves')->name('leaves.')->group(function () {
            Route::get('/', [LeaveController::class, 'index'])->name('index');
            Route::get('/create', [LeaveController::class, 'create'])->name('create');
            Route::post('/', [LeaveController::class, 'store'])->name('store');
            Route::post('/balances/allocate', [LeaveController::class, 'allocateBalances'])
                ->name('balances.allocate');
            Route::get('/balances', [LeaveController::class, 'balances'])->name('balances');
            Route::get('/{leave}', [LeaveController::class, 'show'])->name('show');
            Route::post('/{leave}/approve', [LeaveController::class, 'approve'])->name('approve');
            Route::post('/{leave}/reject', [LeaveController::class, 'reject'])->name('reject');
            Route::post('/{leave}/cancel', [LeaveController::class, 'cancel'])->name('cancel');
        });

        // ── Attendance ─────────────────────────────────────────────
        Route::prefix('attendance')->name('attendance.')->group(function () {
            Route::get('/', [AttendanceController::class, 'index'])->name('index');
            Route::get('/monthly', [AttendanceController::class, 'monthly'])->name('monthly');
            Route::post('/', [AttendanceController::class, 'store'])->name('store');
            Route::post('/bulk', [AttendanceController::class, 'bulkStore'])->name('bulk');
            Route::patch('/{attendance}/regularize', [AttendanceController::class, 'regularize'])->name('regularize');
            Route::get('/employee/{employee}', [AttendanceController::class, 'summary'])->name('summary');
        });
        // ── Salary ─────────────────────────────────────────────────
        Route::prefix('salary')->name('salary.')->group(function () {
            Route::get('/', [SalaryController::class, 'index'])->name('index');
            Route::get('/employee/{employee}', [SalaryController::class, 'show'])->name('show');
            Route::get('/employee/{employee}/create', [SalaryController::class, 'create'])->name('create');
            Route::post('/employee/{employee}', [SalaryController::class, 'store'])->name('store');
            Route::get('/employee/{employee}/{structure}/edit', [SalaryController::class, 'edit'])->name('edit');
            Route::put('/employee/{employee}/{structure}', [SalaryController::class, 'update'])->name('update');
        });
        // ── Payroll ─────────────────────────────────────────────────
        Route::prefix('payroll')->name('payroll.')->group(function () {
            Route::get('/', [PayrollController::class, 'index'])->name('index');
            Route::get('/create', [PayrollController::class, 'create'])->name('create');
            Route::post('/', [PayrollController::class, 'store'])->name('store');
            Route::get('/{payroll}', [PayrollController::class, 'show'])->name('show');
            Route::post('/{payroll}/approve', [PayrollController::class, 'approve'])->name('approve');
            Route::post('/{payroll}/mark-paid', [PayrollController::class, 'markPaid'])->name('mark-paid');
            Route::get('/payslip/{payslip}', [PayrollController::class, 'payslip'])->name('payslip');
            Route::get('/payslip/{payslip}/print', [PayrollController::class, 'printPayslip'])->name('payslip.print');
        });

        // ── Disciplinary ───────────────────────────────────────────
        Route::prefix('disciplinary')->name('disciplinary.')->group(function () {
            Route::get('/', [DisciplinaryController::class, 'index'])->name('index');
            Route::get('/create', [DisciplinaryController::class, 'create'])->name('create');
            Route::post('/', [DisciplinaryController::class, 'store'])->name('store');
            Route::get('/{disciplinary}', [DisciplinaryController::class, 'show'])->name('show');
            Route::get('/{disciplinary}/edit', [DisciplinaryController::class, 'edit'])->name('edit');
            Route::put('/{disciplinary}', [DisciplinaryController::class, 'update'])->name('update');
            Route::post('/{disciplinary}/response', [DisciplinaryController::class, 'recordResponse'])->name('response');
            Route::post('/{disciplinary}/resolve', [DisciplinaryController::class, 'resolve'])->name('resolve');
            Route::post('/{disciplinary}/appeal', [DisciplinaryController::class, 'appeal'])->name('appeal');
        });

        // ── Holidays ───────────────────────────────────────────────
        Route::prefix('holidays')->name('holidays.')->group(function () {
            Route::get('/', [HolidayController::class, 'index'])->name('index');
            Route::post('/', [HolidayController::class, 'store'])->name('store');
            Route::put('/{holiday}', [HolidayController::class, 'update'])->name('update');
            Route::delete('/{holiday}', [HolidayController::class, 'destroy'])->name('destroy');
        });

    });
});
