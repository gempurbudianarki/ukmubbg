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

Route::get('/divisi', [DivisionController::class, 'index'])->name('divisions.index');
Route::get('/divisi/{slug}', [DivisionController::class, 'show'])->name('divisions.show');

// Ecosystem Public Subsystems
Route::get('/proyek', [\App\Http\Controllers\ProjectController::class, 'index'])->name('projects.index');
Route::get('/events', [\App\Http\Controllers\EventController::class, 'index'])->name('events.index');
Route::get('/pengurus', [\App\Http\Controllers\OfficerController::class, 'index'])->name('officers.index');
Route::get('/galeri', [\App\Http\Controllers\GalleryController::class, 'index'])->name('galleries.index');
Route::get('/verifikasi', [\App\Http\Controllers\CertificateController::class, 'verify'])->name('certificates.verify');

Route::get('/berita', [PostController::class, 'index'])->name('posts.index');
Route::get('/berita/{slug}', [PostController::class, 'show'])->name('posts.show');

// Recruitment Flow
Route::get('/pendaftaran', [RecruitmentController::class, 'index'])->name('recruitment.index');
Route::post('/pendaftaran', [RecruitmentController::class, 'store'])->name('recruitment.store');
Route::get('/pendaftaran/sukses/{code}', [RecruitmentController::class, 'success'])->name('recruitment.success');
Route::get('/pendaftaran/cek-status', [RecruitmentController::class, 'status'])->name('recruitment.status');

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Admin CMS Routes (Protected)
Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/recruitment/toggle', [DashboardController::class, 'toggleRecruitment'])->name('recruitment.toggle');

    // Posts Management
    Route::resource('posts', PostAdminController::class)->except(['show']);

    // Divisions Profile & Bio Management
    Route::get('/divisions', [DivisionAdminController::class, 'index'])->name('divisions.index');
    Route::get('/divisions/{division}/edit', [DivisionAdminController::class, 'edit'])->name('divisions.edit');
    Route::put('/divisions/{division}', [DivisionAdminController::class, 'update'])->name('divisions.update');

    // Recruitment Hub & CSV Export
    Route::get('/recruitment/export', [RecruitmentAdminController::class, 'export'])->name('recruitment.export');
    Route::get('/recruitment', [RecruitmentAdminController::class, 'index'])->name('recruitment.index');
    Route::get('/recruitment/{recruitment}', [RecruitmentAdminController::class, 'show'])->name('recruitment.show');
    Route::put('/recruitment/{recruitment}/status', [RecruitmentAdminController::class, 'updateStatus'])->name('recruitment.updateStatus');
    Route::post('/recruitment/{recruitment}/convert-to-member', [RecruitmentAdminController::class, 'convertToMember'])->name('recruitment.convertToMember');

    // Member Management
    Route::get('/members', [\App\Http\Controllers\Admin\MemberAdminController::class, 'index'])->name('members.index');
    Route::post('/members', [\App\Http\Controllers\Admin\MemberAdminController::class, 'store'])->name('members.store');
    Route::put('/members/{member}/status', [\App\Http\Controllers\Admin\MemberAdminController::class, 'updateStatus'])->name('members.updateStatus');
    Route::delete('/members/{member}', [\App\Http\Controllers\Admin\MemberAdminController::class, 'destroy'])->name('members.destroy');

    // Ecosystem Modules
    Route::resource('projects', \App\Http\Controllers\Admin\ProjectAdminController::class)->except(['show']);
    Route::resource('events', \App\Http\Controllers\Admin\EventAdminController::class)->except(['show']);

    Route::get('/officers', [\App\Http\Controllers\Admin\OfficerAdminController::class, 'index'])->name('officers.index');
    Route::post('/officers', [\App\Http\Controllers\Admin\OfficerAdminController::class, 'store'])->name('officers.store');
    Route::delete('/officers/{officer}', [\App\Http\Controllers\Admin\OfficerAdminController::class, 'destroy'])->name('officers.destroy');

    Route::get('/certificates', [\App\Http\Controllers\Admin\CertificateAdminController::class, 'index'])->name('certificates.index');
    Route::post('/certificates', [\App\Http\Controllers\Admin\CertificateAdminController::class, 'store'])->name('certificates.store');
    Route::delete('/certificates/{certificate}', [\App\Http\Controllers\Admin\CertificateAdminController::class, 'destroy'])->name('certificates.destroy');

    Route::get('/galleries', [\App\Http\Controllers\Admin\GalleryAdminController::class, 'index'])->name('galleries.index');
    Route::post('/galleries', [\App\Http\Controllers\Admin\GalleryAdminController::class, 'store'])->name('galleries.store');
    Route::delete('/galleries/{gallery}', [\App\Http\Controllers\Admin\GalleryAdminController::class, 'destroy'])->name('galleries.destroy');
});
