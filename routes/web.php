<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DivisionAdminController;
use App\Http\Controllers\Admin\PostAdminController;
use App\Http\Controllers\Admin\RecruitmentAdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DivisionController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\RecruitmentController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - UKM Ilmu Komputer
|--------------------------------------------------------------------------
*/

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/tentang', [HomeController::class, 'about'])->name('about');
Route::get('/developer', [HomeController::class, 'developer'])->name('developer');

Route::get('/divisi', [DivisionController::class, 'index'])->name('divisions.index');
Route::get('/divisi/{slug}', [DivisionController::class, 'show'])->name('divisions.show');

// Ecosystem Public Subsystems
Route::get('/proyek', [\App\Http\Controllers\ProjectController::class, 'index'])->name('projects.index');
Route::get('/berita', [PostController::class, 'index'])->name('posts.index');
Route::get('/berita/{slug}', [PostController::class, 'show'])->name('posts.show');
Route::get('/events', [\App\Http\Controllers\EventController::class, 'index'])->name('events.index');
Route::get('/pengurus', [\App\Http\Controllers\OfficerController::class, 'index'])->name('officers.index');
Route::get('/galeri', [\App\Http\Controllers\GalleryController::class, 'index'])->name('galleries.index');
Route::get('/verifikasi', [\App\Http\Controllers\CertificateController::class, 'verify'])->name('certificates.verify');

// Recruitment Flow
Route::get('/pendaftaran', [RecruitmentController::class, 'index'])->name('recruitment.index');
Route::post('/pendaftaran', [RecruitmentController::class, 'store'])->name('recruitment.store');
Route::get('/pendaftaran/sukses/{code}', [RecruitmentController::class, 'success'])->name('recruitment.success');
Route::get('/pendaftaran/cek-status', [RecruitmentController::class, 'status'])->name('recruitment.status');

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::match(['get', 'post'], '/logout', [AuthController::class, 'logout'])->name('logout');

// Student Portal Routes (Authenticated)
Route::middleware('auth')->prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Student\StudentDashboardController::class, 'index'])->name('dashboard');
    Route::get('/kta', [\App\Http\Controllers\Student\StudentDashboardController::class, 'kta'])->name('kta');
    Route::get('/presensi', [\App\Http\Controllers\Student\StudentDashboardController::class, 'presensi'])->name('presensi');
    Route::post('/presensi/checkin', [\App\Http\Controllers\Student\StudentDashboardController::class, 'selfCheckin'])->name('presensi.checkin');
    Route::post('/presensi/permission', [\App\Http\Controllers\Student\StudentDashboardController::class, 'submitPermission'])->name('presensi.permission');
    Route::get('/silabus', [\App\Http\Controllers\Student\StudentDashboardController::class, 'silabus'])->name('silabus');
    Route::get('/profile', [\App\Http\Controllers\Student\StudentProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [\App\Http\Controllers\Student\StudentProfileController::class, 'update'])->name('profile.update');

    // Student Project Showcase
    Route::get('/projects', [\App\Http\Controllers\Student\StudentProjectController::class, 'index'])->name('projects.index');
    Route::get('/projects/create', [\App\Http\Controllers\Student\StudentProjectController::class, 'create'])->name('projects.create');
    Route::post('/projects', [\App\Http\Controllers\Student\StudentProjectController::class, 'store'])->name('projects.store');
    Route::get('/projects/{project}/edit', [\App\Http\Controllers\Student\StudentProjectController::class, 'edit'])->name('projects.edit');
    Route::put('/projects/{project}', [\App\Http\Controllers\Student\StudentProjectController::class, 'update'])->name('projects.update');
    Route::delete('/projects/{project}', [\App\Http\Controllers\Student\StudentProjectController::class, 'destroy'])->name('projects.destroy');
});

// Admin CMS Routes (Protected)
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/recruitment/toggle', [DashboardController::class, 'toggleRecruitment'])->name('recruitment.toggle');

    // Posts Management
    Route::post('/posts/{post}/toggle-status', [PostAdminController::class, 'toggleStatus'])->name('posts.toggleStatus');
    Route::resource('posts', PostAdminController::class)->except(['show']);

    // Divisions Profile & Bio Management
    Route::get('/divisions', [DivisionAdminController::class, 'index'])->name('divisions.index');
    Route::get('/divisions/{division}/edit', [DivisionAdminController::class, 'edit'])->name('divisions.edit');
    Route::put('/divisions/{division}', [DivisionAdminController::class, 'update'])->name('divisions.update');

    // Recruitment Hub & CSV Export
    Route::get('/recruitment/export', [RecruitmentAdminController::class, 'export'])->name('recruitment.export');
    Route::get('/recruitment/settings', [RecruitmentAdminController::class, 'settings'])->name('recruitment.settings');
    Route::post('/recruitment/settings', [RecruitmentAdminController::class, 'updateSettings'])->name('recruitment.settings.update');
    Route::get('/recruitment', [RecruitmentAdminController::class, 'index'])->name('recruitment.index');
    Route::get('/recruitment/{recruitment}', [RecruitmentAdminController::class, 'show'])->name('recruitment.show');
    Route::put('/recruitment/{recruitment}/status', [RecruitmentAdminController::class, 'updateStatus'])->name('recruitment.updateStatus');
    Route::post('/recruitment/{recruitment}/convert-to-member', [RecruitmentAdminController::class, 'convertToMember'])->name('recruitment.convertToMember');

    // Member Management & Dossier
    Route::get('/members/export', [\App\Http\Controllers\Admin\MemberAdminController::class, 'export'])->name('members.export');
    Route::get('/members', [\App\Http\Controllers\Admin\MemberAdminController::class, 'index'])->name('members.index');
    Route::post('/members', [\App\Http\Controllers\Admin\MemberAdminController::class, 'store'])->name('members.store');
    Route::get('/members/{member}', [\App\Http\Controllers\Admin\MemberAdminController::class, 'show'])->name('members.show');
    Route::get('/members/{member}/edit', [\App\Http\Controllers\Admin\MemberAdminController::class, 'edit'])->name('members.edit');
    Route::put('/members/{member}', [\App\Http\Controllers\Admin\MemberAdminController::class, 'update'])->name('members.update');
    Route::put('/members/{member}/status', [\App\Http\Controllers\Admin\MemberAdminController::class, 'updateStatus'])->name('members.updateStatus');
    Route::delete('/members/{member}', [\App\Http\Controllers\Admin\MemberAdminController::class, 'destroy'])->name('members.destroy');

    // Attendance & Presensi System
    Route::get('/attendance', [\App\Http\Controllers\Admin\AttendanceAdminController::class, 'index'])->name('attendance.index');
    Route::get('/attendance/create', [\App\Http\Controllers\Admin\AttendanceAdminController::class, 'create'])->name('attendance.create');
    Route::post('/attendance', [\App\Http\Controllers\Admin\AttendanceAdminController::class, 'store'])->name('attendance.store');
    Route::get('/attendance/{session}', [\App\Http\Controllers\Admin\AttendanceAdminController::class, 'show'])->name('attendance.show');
    Route::get('/attendance/{session}/bap', [\App\Http\Controllers\Admin\AttendanceAdminController::class, 'bap'])->name('attendance.bap');
    Route::put('/attendance/{session}/logs', [\App\Http\Controllers\Admin\AttendanceAdminController::class, 'updateLogs'])->name('attendance.updateLogs');
    Route::post('/attendance/{session}/quick-mark', [\App\Http\Controllers\Admin\AttendanceAdminController::class, 'quickMark'])->name('attendance.quickMark');
    Route::post('/attendance/{session}/toggle-status', [\App\Http\Controllers\Admin\AttendanceAdminController::class, 'toggleStatus'])->name('attendance.toggleStatus');
    Route::post('/attendance/{session}/update-passcode', [\App\Http\Controllers\Admin\AttendanceAdminController::class, 'updatePasscode'])->name('attendance.updatePasscode');
    Route::delete('/attendance/{session}', [\App\Http\Controllers\Admin\AttendanceAdminController::class, 'destroy'])->name('attendance.destroy');

    // Announcements Management
    Route::get('/announcements', [\App\Http\Controllers\Admin\AnnouncementAdminController::class, 'index'])->name('announcements.index');
    Route::post('/announcements', [\App\Http\Controllers\Admin\AnnouncementAdminController::class, 'store'])->name('announcements.store');
    Route::delete('/announcements/{announcement}', [\App\Http\Controllers\Admin\AnnouncementAdminController::class, 'destroy'])->name('announcements.destroy');

    // Ecosystem Modules
    Route::post('/projects/{project}/moderate', [\App\Http\Controllers\Admin\ProjectAdminController::class, 'moderate'])->name('projects.moderate');
    Route::post('/projects/{project}/toggle-featured', [\App\Http\Controllers\Admin\ProjectAdminController::class, 'toggleFeatured'])->name('projects.toggleFeatured');
    Route::resource('projects', \App\Http\Controllers\Admin\ProjectAdminController::class)->except(['show']);
    Route::resource('events', \App\Http\Controllers\Admin\EventAdminController::class)->except(['show']);

    Route::get('/officers', [\App\Http\Controllers\Admin\OfficerAdminController::class, 'index'])->name('officers.index');
    Route::post('/officers', [\App\Http\Controllers\Admin\OfficerAdminController::class, 'store'])->name('officers.store');
    Route::get('/officers/{officer}/edit', [\App\Http\Controllers\Admin\OfficerAdminController::class, 'edit'])->name('officers.edit');
    Route::put('/officers/{officer}', [\App\Http\Controllers\Admin\OfficerAdminController::class, 'update'])->name('officers.update');
    Route::delete('/officers/{officer}', [\App\Http\Controllers\Admin\OfficerAdminController::class, 'destroy'])->name('officers.destroy');

    Route::get('/certificates', [\App\Http\Controllers\Admin\CertificateAdminController::class, 'index'])->name('certificates.index');
    Route::post('/certificates', [\App\Http\Controllers\Admin\CertificateAdminController::class, 'store'])->name('certificates.store');
    Route::get('/certificates/{certificate}/edit', [\App\Http\Controllers\Admin\CertificateAdminController::class, 'edit'])->name('certificates.edit');
    Route::put('/certificates/{certificate}', [\App\Http\Controllers\Admin\CertificateAdminController::class, 'update'])->name('certificates.update');
    Route::delete('/certificates/{certificate}', [\App\Http\Controllers\Admin\CertificateAdminController::class, 'destroy'])->name('certificates.destroy');

    Route::get('/galleries', [\App\Http\Controllers\Admin\GalleryAdminController::class, 'index'])->name('galleries.index');
    Route::post('/galleries', [\App\Http\Controllers\Admin\GalleryAdminController::class, 'store'])->name('galleries.store');
    Route::get('/galleries/{gallery}/edit', [\App\Http\Controllers\Admin\GalleryAdminController::class, 'edit'])->name('galleries.edit');
    Route::put('/galleries/{gallery}', [\App\Http\Controllers\Admin\GalleryAdminController::class, 'update'])->name('galleries.update');
    Route::delete('/galleries/{gallery}', [\App\Http\Controllers\Admin\GalleryAdminController::class, 'destroy'])->name('galleries.destroy');

    // User & Role Management (Super Admin Only)
    Route::get('/users', [\App\Http\Controllers\Admin\UserAdminController::class, 'index'])->name('users.index');
    Route::post('/users', [\App\Http\Controllers\Admin\UserAdminController::class, 'store'])->name('users.store');
    Route::put('/users/{user}', [\App\Http\Controllers\Admin\UserAdminController::class, 'update'])->name('users.update');
    Route::put('/users/{user}/assign-role', [\App\Http\Controllers\Admin\UserAdminController::class, 'assignRole'])->name('users.assignRole');
    Route::post('/users/{user}/reset-password', [\App\Http\Controllers\Admin\UserAdminController::class, 'resetPassword'])->name('users.resetPassword');
    Route::delete('/users/{user}', [\App\Http\Controllers\Admin\UserAdminController::class, 'destroy'])->name('users.destroy');
});
