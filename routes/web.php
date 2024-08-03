<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ExcelController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SupportController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
})->name('home');

Route::post('login', [AuthController::class, 'login'])->name('login');

Route::post('logout', [AuthController::class, 'logout'])->name('logout');

Route::get('forgot-password', function () {
    return view('auth.forgot-password');
})->name('password.request');

Route::post('forgot-password', [AuthController::class, 'forgotPassword'])
    ->name('password.email');

Route::get('reset-password/{token}', [AuthController::class, 'resetPasswordView'])
    ->name('password.reset');

Route::post('reset-password', [AuthController::class, 'resetPassword'])
    ->name('password.store');

Route::middleware('auth')->group(function () {
    // Common routes accessible to both admin and faculty
    Route::get('upload', [DashboardController::class, 'uploadView'])->name('upload');
    Route::post('upload', [ExcelController::class, 'fileUpload'])->name('file-upload');

    Route::get('fetch', [DashboardController::class, 'fetchView'])->name('fetch');
    Route::post('fetch', [DashboardController::class, 'fetchData'])->name('fetch-data');

    Route::get('co-attainment/{cid}/{batch}', [DashboardController::class, 'getCOAttainment'])->name('co.attainment');
    Route::get('final-co-attainment/{cid}/{batch}', [DashboardController::class, 'getFinalCOAttainment'])->name('final.co.attainment');
    Route::get('po-attainment/{cid}/{batch}', [DashboardController::class, 'getPOAttainment'])->name('po.attainment');

    Route::get('co_po_relation', [DashboardController::class, 'coPoRelation'])->name('co_po_relation');

    Route::get('analysis/BCA', [DashboardController::class, 'bcaAnalysis'])->name('bca-analysis');
    Route::get('analysis/MCA', [DashboardController::class, 'mcaAnalysis'])->name('mca-analysis');

    Route::get('direct-po-attainment', [DashboardController::class, 'directPOAttainment'])->name('directPOAttainment');

    Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    Route::get('support', [SupportController::class, 'index'])->name('support');
    Route::post('support/create-ticket', [SupportController::class, 'create'])->name('support.ticket.create');
});

Route::middleware('auth')->group(function () {
    Route::middleware('admin')->prefix('admin')->group(function () {
        Route::get('dashboard', [DashboardController::class, 'index'])->name('admin-dashboard');

        Route::get('tables', [DashboardController::class, 'tables'])->name('tables');

        Route::get('manage-subjects', [DashboardController::class, 'manageSubjects'])->name('manage-subjects');
        Route::post('manage-subjects/add', [DashboardController::class, 'addSubject'])->name('add-subject');
        Route::post('manage-subjects/update', [DashboardController::class, 'updateSubject'])->name('update.subject.info');
        Route::delete('manage-subjects/delete', [DashboardController::class, 'deleteSubject'])->name('delete.subject');

        Route::get('manage-faculty', [DashboardController::class, 'manageFaculty'])->name('manage-faculty');
        Route::post('manage-faculty/add', [DashboardController::class, 'addFaculty'])->name('add-faculty');
        Route::patch('manage-faculty/update', [DashboardController::class, 'updateFaculty'])->name('update-faculty');
        Route::post('manage-faculty/delete', [DashboardController::class, 'deleteFaculty'])->name('delete-faculty');

        // Route::get('read', [ExcelController::class, 'readDbData'])->name('admin-readDbData');

        Route::get('assign-subject', [DashboardController::class, 'assignSubjectView'])->name('assign-subject');
        Route::post('assign-subject', [DashboardController::class, 'assignSubject'])->name('assign-subject');
        Route::post('assign-subject/update', [DashboardController::class, 'assignSubjectUpdate'])->name('edit-assign-subject');

        // export as excel
        Route::get('/export/{course}/{batch}', [ExcelController::class, 'export'])->name('direct-attainment-export');



        // ajax requests
        Route::get('getCourseInfo/{id}', [DashboardController::class, 'getCourseInfo']);
        Route::get('getFacultyInfo/{id}', [DashboardController::class, 'getFacultyInfo']);
        Route::get('get_assigned_courses', [DashboardController::class, 'getAssignedSubjects']);

        Route::get('direct-attainment', function () {
            return view('direct-attainment');
        })->name('direct-attainment');

        Route::post('get-direct-attainment', [DashboardController::class, 'directPOAttainment'])->name('get-direct-attainment');

        Route::get('/get-faculty-info', [DashboardController::class, 'facultyInfo']);
        Route::get('/get-co_po_relation', [DashboardController::class, 'getCourses']);
        Route::get('/get-assigned-faculty', [DashboardController::class, 'getAssignedFaculty']);
    });

    Route::get('/getSubjectData/{cid}', [DashboardController::class, 'getSubjectData']);
    Route::get('/getSubjects/{course}', [DashboardController::class, 'getSubjects']);

    Route::middleware('faculty')->group(function () {
        Route::get('dashboard', [DashboardController::class, 'userDashboard'])->name('dashboard');
    });

    // ajax requests
    Route::get('get_CO_PO_Relation/{courseId}', [DashboardController::class, 'getCoPoRelation']);
    Route::post('update-co-po-relation', [DashboardController::class, 'updateCoPoRelation'])->name('update-co-po-relation');
    Route::get('/course/search', [DashboardController::class, 'searchCourses']);

    Route::get('/test', [DashboardController::class, 'testPage'])->name('testPage');

    Route::get('/export', [ExcelController::class, 'export'])->name('export-table');

    Route::get('/export-courses', [ExcelController::class, 'exportCourses']);


    // Admin PO Attainment

    Route::get('subject-report', function () {
        return view('subject-report');
    })->name('subject-report');

    Route::get('dev-profile', function () {
        return view('dev-profile');
    })->name('dev-profile');

    Route::get('error-404', function () {
        return view('error-404');
    })->name('error-404');

    Route::middleware('support')->prefix('support')->group(function () {
        Route::get('dashboard', [SupportController::class, 'dashboard'])->name('support-dashboard');
        Route::get('all-tickets', [SupportController::class, 'allTickets'])->name('support-tickets');
        Route::get('pending', [SupportController::class, 'pending'])->name('support-pending');
        Route::get('resolved', [SupportController::class, 'resolved'])->name('support-resolved');
        Route::get('view/{id}', [SupportController::class, 'view'])->name('support-ticket-view');
        Route::put('update/{id}', [SupportController::class, 'updateStatus'])->name('support-ticket-update-status');
    });

    Route::get('confirm-password', function(){
        return view('auth.confirm-password');
    })->name('password.confirm');

    // Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    Route::put('password', [ProfileController::class, 'updatePassword'])->name('password.update');
});
