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
        return redirect('/admin/dashboard');
    })->name('dashboard');

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

    // ── Academic Module (Class Routine) ──────────────────────────────────────
    Route::get('/admin/academic/class-routine-builder', \App\Livewire\Admin\Academic\ClassRoutineBuilder::class)->name('admin.academic.class-routine-builder');

    // ── Employee Management (Teachers & Staff) ───────────────────────────────
    Route::get('/admin/employees', \App\Livewire\Admin\Employee\EmployeeList::class)->name('admin.employees');
    Route::get('/admin/employees/create', \App\Livewire\Admin\Employee\EmployeeWizard::class)->name('admin.employees.create');

    Route::view('/student/dashboard', 'dashboard')->name('student.dashboard');
});

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';

