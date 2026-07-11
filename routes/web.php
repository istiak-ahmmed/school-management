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
    Route::get('/admin/students/{student}', \App\Livewire\Admin\Student\StudentProfile::class)->name('admin.students.profile');
    Route::get('/admin/students/{student}/edit', \App\Livewire\Admin\Student\StudentEdit::class)->name('admin.students.edit');

    // ── Attendance System ───────────────────────────────────────────────────
    Route::get('/admin/attendance/mark', \App\Livewire\Admin\Attendance\MarkAttendance::class)->name('admin.attendance.mark');
    Route::get('/admin/attendance/report', \App\Livewire\Admin\Attendance\AttendanceReport::class)->name('admin.attendance.report');

    Route::view('/student/dashboard', 'dashboard')->name('student.dashboard');
});

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';

