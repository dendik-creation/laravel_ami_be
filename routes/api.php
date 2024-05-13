<?php

use App\Http\Controllers\AuditeeController;
use App\Http\Controllers\AuditorController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClausulController;
use App\Http\Controllers\DepartmenController;
use App\Http\Controllers\GrupAuditorController;
use App\Http\Controllers\IsoController;
use App\Http\Controllers\ManagementController;
use App\Http\Controllers\PDDController;
use App\Http\Controllers\ProfileContoller;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Auth Route
Route::group(['prefix' => 'auth'], function () {
    Route::post('/login', [AuthController::class, 'login'])->middleware('guest');
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
});

// Only Access If Logged
Route::group(['middleware' => 'auth:sanctum'], function () {

    Route::group(['prefix' => 'profile'], function(){
        Route::get('my-profile', [ProfileContoller::class, 'myProfile']);
        Route::put('update-profile', [ProfileContoller::class, 'updateProfile']);
        Route::post('check-password', [ProfileContoller::class, 'checkPassword']);
        Route::post('change-password', [ProfileContoller::class, 'changePassword']);
    });

    // Auditor Route Group
    Route::group(['prefix' => 'auditor'], function () {
        Route::get('/dashboard', [AuditorController::class, 'dashboard']);
        Route::post('/new-audit', [AuditorController::class, 'newAudit']);
        Route::get('/monitoring/data-auditor', [AuditorController::class, 'auditorList']);
        Route::get('/monitoring/histori-audit', [AuditorController::class, 'historiAudit']);
        Route::get('/monitoring/histori-audit/{id}', [AuditorController::class, 'historiAuditDetail']);
        Route::get('/monitoring/histori-audit/edit/{id}', [AuditorController::class, 'historiAuditEdit']);
        Route::delete('/monitoring/histori-audit/{id}', [AuditorController::class, 'destroy']);

        Route::post('/download/attachment', [AuditeeController::class, 'downloadAttachment']);


        // Auditor New Audit Form Route Manage Data
        Route::get('/new-audit-data', [AuditorController::class, 'newAuditData']);
        Route::get('/edit-audit-data/{id}', [AuditorController::class, 'editAuditData']);
        Route::get('/pihak-terlibat/{auditee_id}', [AuditorController::class, 'pihakTerlibat']);
        Route::get('/get-auditee-sub-dept/{dept_id}', [AuditorController::class, 'getSubDeptByAuditeeDept']);
        Route::get('/history-dept-audit/{dept_id}', [AuditorController::class, 'historyAuditDept']);
    });

    // Auditee Route Group
    Route::group(['prefix' => 'auditee'], function () {
        Route::get('/dashboard', [AuditeeController::class, 'dashboard']);
        Route::get('/respon-audit', [AuditeeController::class, 'responAuditGet']);
        Route::get('/respon-audit/{id}', [AuditeeController::class, 'responAuditGetOne']);
        Route::put('/respon-audit/{header_id}', [AuditeeController::class, 'responAuditUpdate']);
        Route::post('/respon-audit/docs', [AuditeeController::class, 'responAuditUpdateDocs']);
        Route::get('/monitoring/data-auditor', [AuditeeController::class, 'auditorList']);
        Route::get('/monitoring/histori-audit', [AuditeeController::class, 'historiAudit']);
        Route::get('/monitoring/histori-audit/{id}', [AuditeeController::class, 'historiAuditDetail']);
        Route::get('/monitoring/histori-audit/histori-dept/{dept_id}', [AuditeeController::class, 'historiAuditByDept']);

        Route::post('/download/attachment', [AuditeeController::class, 'downloadAttachment']);
        Route::get('/get/attachment', [AuditeeController::class, 'getAttachment']);
    });

    // PDD Route Group
    Route::group(['prefix' => 'pdd'], function () {
        Route::get('/audit-list-close', [ManagementController::class, 'auditCloses']);
        Route::put('/continue-audit/{header_id}', [ManagementController::class, 'continueAudit']);

        Route::get('/dashboard', [PDDController::class, 'dashboard']);
        Route::get('/monitoring/histori-audit', [PDDController::class, 'historiAudit']);
        Route::get('/monitoring/histori-audit/{id}', [PDDController::class, 'historiAuditDetail']);

        Route::post('/download/attachment', [AuditeeController::class, 'downloadAttachment']);


        Route::post('/reminder-auditee', [ManagementController::class, 'reminderAuditee']);
        Route::post('/broadcast-auditee', [ManagementController::class, 'broadcastAuditee']);

        // Crud User
        Route::resource('/master-data/data-user', UserController::class);
        Route::post('/master-data/data-user/reset-pass/{id}', [UserController::class, 'resetPass']);
        // Edit User With Departemen List
        Route::get('/dept-list-select', [DepartmenController::class, 'deptList']);
        Route::get('/grup-auditor-list-select', [GrupAuditorController::class, 'grupAuditorList']);

        // Crud Clausul
        Route::resource('/master-data/data-clausul', ClausulController::class)->except('destroy');
        Route::delete('/master-data/data-clausul/{id}', [ClausulController::class, 'destroy']);
        Route::post('/master-data/data-clausul/destroy-custom', [ClausulController::class, 'destroyCustom']);
        // Crud Grup Auditor
        Route::resource('/master-data/data-grup-auditor', GrupAuditorController::class)->except('destroy');
        // Edit Grup Auditor With Auditor List
        Route::get('/auditor-list-select', [AuditorController::class, 'auditorListSelect']);
        Route::delete('/master-data/data-grup-auditor/{id}', [GrupAuditorController::class, 'destroy']);
        Route::post('/master-data/data-grup-auditor/update-create/{id}', [GrupAuditorController::class, 'updateCreate']);
        Route::post('/master-data/data-grup-auditor/destroy-member', [GrupAuditorController::class, 'destroyMember']);

        // Crud Iso
        Route::resource('/master-data/data-iso', IsoController::class);

        // Crud Departemen
        Route::resource('/master-data/data-departemen', DepartmenController::class);
        Route::post('/master-data/data-departemen/destroy-sub-dept', [DepartmenController::class, 'removeSubDept']);

        // Crud Unit
        Route::resource('/master-data/data-unit', UnitController::class)->except('show');
        Route::get('/master-data/data-unit/for-select', [UnitController::class, 'unitSelect']);
    });

    // Management Route Group
    Route::group(['prefix' => 'management'], function () {
        Route::get('/audit-list-close', [ManagementController::class, 'auditCloses']);
        Route::put('/continue-audit/{header_id}', [ManagementController::class, 'continueAudit']);

        Route::post('/download/attachment', [AuditeeController::class, 'downloadAttachment']);


        // Emails
        Route::post('/reminder-auditee', [ManagementController::class, 'reminderAuditee']);
        Route::post('/broadcast-auditee', [ManagementController::class, 'broadcastAuditee']);

        Route::get('/dashboard', [PDDController::class, 'dashboard']);
        Route::get('/monitoring/histori-audit', [PDDController::class, 'historiAudit']);
        Route::get('/monitoring/histori-audit/{id}', [PDDController::class, 'historiAuditDetail']);
        // Crud User
        Route::resource('/master-data/data-user', UserController::class);
        Route::post('/master-data/data-user/reset-pass/{id}', [UserController::class, 'resetPass']);

        // Edit User With Departemen List
        Route::get('/dept-list-select', [DepartmenController::class, 'deptList']);
        Route::get('/grup-auditor-list-select', [GrupAuditorController::class, 'grupAuditorList']);

        // Crud Clausul
        Route::resource('/master-data/data-clausul', ClausulController::class)->except('destroy');
        Route::delete('/master-data/data-clausul/{id}', [ClausulController::class, 'destroy']);
        Route::post('/master-data/data-clausul/destroy-custom', [ClausulController::class, 'destroyCustom']);
        // Crud Grup Auditor
        Route::resource('/master-data/data-grup-auditor', GrupAuditorController::class)->except('destroy');
        // Edit Grup Auditor With Auditor List
        Route::get('/auditor-list-select', [AuditorController::class, 'auditorListSelect']);
        Route::delete('/master-data/data-grup-auditor/{id}', [GrupAuditorController::class, 'destroy']);
        Route::post('/master-data/data-grup-auditor/update-create/{id}', [GrupAuditorController::class, 'updateCreate']);
        Route::post('/master-data/data-grup-auditor/destroy-member', [GrupAuditorController::class, 'destroyMember']);

        // Crud Iso
        Route::resource('/master-data/data-iso', IsoController::class);

        // Crud Departemen
        Route::resource('/master-data/data-departemen', DepartmenController::class);
        Route::post('/master-data/data-departemen/destroy-sub-dept', [DepartmenController::class, 'removeSubDept']);

        // Crud Unit
        Route::resource('/master-data/data-unit', UnitController::class)->except('show');
        Route::get('/master-data/data-unit/for-select', [UnitController::class, 'unitSelect']);
    });
});
// Special Access For Login Route
Route::get('/master-data/data-iso/for-select', [IsoController::class, 'isoListSelect']);
