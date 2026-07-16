<?php

use Illuminate\Support\Facades\Route;

// ── Public: Website Routes ──────────────────────────────────────────────────
Route::get('/', \App\Livewire\Website\Home::class)->name('home');
Route::get('/about', \App\Livewire\Website\About::class)->name('about');
Route::get('/teachers', \App\Livewire\Website\TeacherDirectory::class)->name('teachers');
Route::get('/admission-info', \App\Livewire\Website\AdmissionInfo::class)->name('admission.info');
Route::get('/notices', \App\Livewire\Website\NoticeBoard::class)->name('notices');
Route::get('/results', \App\Livewire\Website\ResultChecker::class)->name('results');
Route::get('/gallery', \App\Livewire\Website\Gallery::class)->name('gallery');
Route::get('/contact', \App\Livewire\Website\Contact::class)->name('contact');

// ── Public: Static Pages ──────────────────────────────────────────────────
Route::view('/privacy-policy', 'pages.privacy-policy')->name('privacy');
Route::view('/terms', 'pages.terms')->name('terms');

// ── Public: Admission Form ────────────────────────────────────────────────
Route::get('/admission', \App\Livewire\Website\AdmissionForm::class)->name('admission.form');

Route::middleware(['auth', 'verified'])->group(function () {
    // We can redirect the old dashboard to the admin dashboard or handle it based on role
    Route::get('/dashboard', function () {
        $user = auth()->user();
        if ($user->hasRole('student')) {
            return redirect('/student/dashboard');
        }
        if ($user->hasRole('teacher')) {
            return redirect('/teacher/dashboard');
        }
        return redirect('/admin/dashboard');
    })->name('dashboard');


    // ── Admin Panel (Blocked for teacher & student roles) ────────────────────
    Route::middleware(['admin.panel'])->group(function () {

        Route::get('/admin/dashboard', \App\Livewire\Admin\Dashboard::class)->name('admin.dashboard');
        Route::get('/admin/academic-years', \App\Livewire\Admin\AcademicYearManager::class)->name('admin.academic-years');
        Route::get('/admin/classes', \App\Livewire\Admin\ClassManager::class)->name('admin.classes');
        Route::get('/admin/sections', \App\Livewire\Admin\SectionManager::class)->name('admin.sections');
        Route::get('/admin/subjects', \App\Livewire\Admin\SubjectManager::class)->name('admin.subjects');

        // ── Admission Management ────────────────────────────────────────────────
        Route::get('/admin/admissions', \App\Livewire\Admin\Admission\ApplicationManager::class)->name('admin.admissions');

        // ── Student Management ──────────────────────────────────────────────────
        Route::get('/admin/students', \App\Livewire\Admin\Student\StudentManager::class)->name('admin.students');
        Route::get('/admin/students/admission', \App\Livewire\Admin\Student\StudentAdmissionWizard::class)->name('admin.students.admission');
        Route::get('/admin/students/promotion', \App\Livewire\Admin\Student\StudentPromotion::class)->name('admin.students.promotion');
        Route::get('/admin/students/{student}', \App\Livewire\Admin\Student\StudentProfile::class)->name('admin.students.profile');
        Route::get('/admin/students/{student}/edit', \App\Livewire\Admin\Student\StudentEdit::class)->name('admin.students.edit');

        // ── Attendance System ───────────────────────────────────────────────────
        Route::get('/admin/attendance/mark', \App\Livewire\Admin\Attendance\MarkAttendance::class)->name('admin.attendance.mark');
        Route::get('/admin/attendance/report', \App\Livewire\Admin\Attendance\AttendanceReport::class)->name('admin.attendance.report');

        // ── Finance Management ──────────────────────────────────────────────────
        Route::get('/admin/finance/fee-collection', \App\Livewire\Admin\Finance\FeeCollection::class)->name('admin.finance.fee-collection');
        Route::get('/admin/finance/invoices', \App\Livewire\Admin\Finance\InvoiceManager::class)->name('admin.finance.invoices');
        Route::get('/admin/finance/invoice/{invoice}/print', [\App\Http\Controllers\Admin\Finance\InvoiceController::class, 'show'])->name('admin.finance.invoice.print');
        Route::get('/admin/finance/receipt/{payment}', [\App\Http\Controllers\Admin\Finance\ReceiptController::class, 'show'])->name('admin.finance.receipt');
        Route::get('/admin/finance/salary-payments', \App\Livewire\Admin\Finance\SalaryPaymentManager::class)->name('admin.finance.salary-payments');
        Route::get('/admin/finance/salary-slip/{payment}', [\App\Http\Controllers\Admin\Finance\SalarySlipController::class, 'show'])->name('admin.finance.salary-slip');

        // ── Examination & Results ───────────────────────────────────────────────
        Route::get('/admin/exams', \App\Livewire\Admin\Exam\ExamManager::class)->name('admin.exams');
        Route::get('/admin/exams/grade-rules', \App\Livewire\Admin\Exam\GradeRuleManager::class)->name('admin.exams.grade-rules');
        Route::get('/admin/exams/routine-builder', \App\Livewire\Admin\Exam\ExamRoutineBuilder::class)->name('admin.exams.routine-builder');
        Route::get('/admin/exams/routine/{exam_id}/{class_id}/print', [\App\Http\Controllers\Admin\Exam\RoutinePrintController::class, 'show'])->name('admin.exams.routine.print');
        Route::get('/admin/exams/marks-entry', \App\Livewire\Admin\Exam\MarksEntry::class)->name('admin.exams.marks-entry');

        // ── Finance & Accounts ──────────────────────────────────────────────────
        Route::get('/admin/finance/fee-types', \App\Livewire\Admin\Finance\FeeTypeManager::class)->name('admin.finance.fee-types');
        Route::get('/admin/finance/fee-structures', \App\Livewire\Admin\Finance\FeeStructureManager::class)->name('admin.finance.fee-structures');
        Route::get('/admin/finance/expense-categories', \App\Livewire\Admin\Finance\ExpenseCategory\CategoryManager::class)->name('admin.finance.expense-categories');
        Route::get('/admin/finance/expenses', \App\Livewire\Admin\Finance\Expense\ExpenseList::class)->name('admin.finance.expenses.index');
        Route::get('/admin/finance/expenses/create', \App\Livewire\Admin\Finance\Expense\ExpenseForm::class)->name('admin.finance.expenses.create');
        Route::get('/admin/finance/expenses/{id}/edit', \App\Livewire\Admin\Finance\Expense\ExpenseForm::class)->name('admin.finance.expenses.edit');
        Route::get('/admin/finance/expenses/report', \App\Livewire\Admin\Finance\Expense\ExpenseReport::class)->name('admin.finance.expenses.report');

        // ── Academic Module (Class Routine) ──────────────────────────────────────
        Route::get('/admin/academic/class-routine-builder', \App\Livewire\Admin\Academic\ClassRoutineBuilder::class)->name('admin.academic.class-routine-builder');

        // ── Employee Management (Teachers & Staff) ───────────────────────────────
        Route::get('/admin/employees', \App\Livewire\Admin\Employee\EmployeeList::class)->name('admin.employees');
        Route::get('/admin/employees/create', \App\Livewire\Admin\Employee\EmployeeWizard::class)->name('admin.employees.create');

        // ── Communication ────────────────────────────────────────────────────────
        Route::get('/admin/communication/notices', \App\Livewire\Admin\Communication\NoticeManager::class)->name('admin.communication.notices');
        Route::get('/admin/communication/sms', \App\Livewire\Admin\Communication\SmsSender::class)->name('admin.communication.sms');

        // ── Settings ─────────────────────────────────────────────────────────────
        Route::get('/admin/settings/roles', \App\Livewire\Admin\Settings\RoleManager::class)->name('admin.settings.roles');
        Route::get('/admin/settings/payment-methods', \App\Livewire\Admin\Settings\PaymentMethodList::class)->name('admin.settings.payment-methods');
        Route::get('/admin/settings/gateway-setup', \App\Livewire\Admin\Settings\GatewaySettings::class)->name('admin.settings.gateway');

        // ── Reports ──────────────────────────────────────────────────────────────
        Route::prefix('admin/reports')->name('admin.reports.')->group(function () {
            Route::get('/', \App\Livewire\Admin\Reports\ReportIndex::class)->name('index');
            Route::get('/student-enrollment', \App\Livewire\Admin\Reports\StudentEnrollmentReport::class)->name('student-enrollment');
            Route::get('/fee-collection', \App\Livewire\Admin\Reports\FeeCollectionReport::class)->name('fee-collection');
            Route::get('/fee-defaulters', \App\Livewire\Admin\Reports\FeeDefaultersReport::class)->name('fee-defaulters');
            Route::get('/income-expense', \App\Livewire\Admin\Reports\IncomeExpenseSummary::class)->name('income-expense');
            Route::get('/exam-result', \App\Livewire\Admin\Reports\ExamResultReport::class)->name('exam-result');
            Route::get('/staff-payroll', \App\Livewire\Admin\Reports\StaffPayrollReport::class)->name('staff-payroll');
            Route::get('/audit-log', \App\Livewire\Admin\Reports\AuditLogReport::class)->name('audit-log');
        });

    }); // end admin.panel middleware

    // ── Teacher Portal ───────────────────────────────────────────────────────
    Route::prefix('teacher')->middleware(['role:teacher'])->group(function () {
        Route::get('/dashboard', \App\Livewire\Teacher\Dashboard::class)->name('teacher.dashboard');
        Route::get('/profile', \App\Livewire\Teacher\MyProfile::class)->name('teacher.profile');
        Route::get('/my-classes', \App\Livewire\Teacher\MyClasses::class)->name('teacher.my-classes');
        Route::get('/attendance', \App\Livewire\Teacher\AttendanceManager::class)->name('teacher.attendance');
        Route::get('/marks-entry', \App\Livewire\Teacher\MarksEntry::class)->name('teacher.marks-entry');
        Route::get('/exam-routine', \App\Livewire\Teacher\ExamRoutine::class)->name('teacher.exam-routine');
        Route::get('/class-routine', \App\Livewire\Teacher\ClassRoutine::class)->name('teacher.class-routine');
        Route::get('/salary', \App\Livewire\Teacher\MySalary::class)->name('teacher.salary');
        Route::get('/notices', \App\Livewire\Teacher\NoticeBoard::class)->name('teacher.notices');
    });

    // ── Student Portal ───────────────────────────────────────────────────────
    Route::prefix('student')->middleware(['role:student'])->group(function () {
        Route::get('/dashboard', \App\Livewire\Student\Dashboard::class)->name('student.dashboard');
        Route::get('/profile', \App\Livewire\Student\MyProfile::class)->name('student.profile');
        Route::get('/attendance', \App\Livewire\Student\MyAttendance::class)->name('student.attendance');
        Route::get('/results', \App\Livewire\Student\MyResults::class)->name('student.results');
        Route::get('/exam-routine', \App\Livewire\Student\ExamRoutine::class)->name('student.exam-routine');
        Route::get('/class-routine', \App\Livewire\Student\ClassRoutine::class)->name('student.class-routine');
        Route::get('/fees', \App\Livewire\Student\MyFees::class)->name('student.fees');
        Route::get('/notices', \App\Livewire\Student\NoticeBoard::class)->name('student.notices');
    });
});

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';

