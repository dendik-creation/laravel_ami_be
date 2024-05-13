<?php

use App\Helpers\AuditeeHelper;
use App\Mail\AuditNotify;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
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
    return response()->json("Hello AMI", 200);
});

Route::get('/mail', function () {
    return view('mail.notify');
    // Mail::to('creationdendik729@gmail.com')->send(new AuditNotify());
});
