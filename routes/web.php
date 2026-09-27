<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

use App\Http\Controllers\ClientController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\DailyPassController;

Route::post('/daily-passes', [DailyPassController::class, 'store'])->name('daily-passes.store');

Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::get('/clientes/create-group', function () {
    return view('clientes.create-group');
})->name('clientes.create.group');
Route::post('/clientes/group', [ClientController::class, 'storeGroup'])->name('clientes.storeGroup');

Route::get('/clientes/search', [ClientController::class, 'search'])->name('clientes.search');
Route::post('/clientes/authorize-abono', [ClientController::class, 'authorizeAbono'])->name('clientes.authorizeAbono');
Route::resource('clientes', ClientController::class)->parameters(['clientes' => 'client']);
Route::get('clientes/{client}/invoice', [ClientController::class, 'invoice'])->name('clientes.invoice');
Route::post('clientes/{client}/toggle-measurements', [ClientController::class, 'toggleMeasurements'])->name('clientes.toggleMeasurements');
Route::post('clientes/{client}/toggle-status', [ClientController::class, 'toggleStatus'])->name('clientes.toggleStatus');
Route::post('clientes/{client}/group/{membership}/remove', [ClientController::class, 'removeFromGroup'])->name('clientes.group.remove');
Route::post('clientes/group/{membership}/add', [ClientController::class, 'addToGroup'])->name('clientes.group.add');

use App\Http\Controllers\MembershipController;

// Membresías & Planes
Route::get('/membresias', [MembershipController::class, 'index'])->name('memberships');
Route::post('/membresias', [MembershipController::class, 'store'])->name('memberships.store');
Route::post('/membresias/{membership}/renew', [MembershipController::class, 'renew'])->name('memberships.renew');
Route::delete('/membresias/{membership}', [MembershipController::class, 'destroy'])->name('memberships.destroy');
Route::post('/planes', [MembershipController::class, 'storePlan'])->name('plans.store');
Route::put('/planes/{plan}', [MembershipController::class, 'updatePlan'])->name('plans.update');
Route::delete('/planes/{plan}', [MembershipController::class, 'destroyPlan'])->name('plans.destroy');
Route::get('/api/clients-search', [MembershipController::class, 'searchClients'])->name('api.clients.search');

use App\Http\Controllers\PaymentController;
use App\Http\Controllers\CashShiftController;

// Finanzas & Control de Caja
Route::get('/pagos', [PaymentController::class, 'index'])->name('pagos');
Route::get('/pagos/arqueo', [PaymentController::class, 'arqueoCaja'])->name('pagos.arqueo');
Route::post('/pagos/authorize-arqueo', [PaymentController::class, 'authorizeArqueo'])->name('pagos.authorizeArqueo');
Route::get('/pagos/{payment}/invoice', [PaymentController::class, 'invoice'])->name('pagos.invoice');
Route::post('/pagos', [PaymentController::class, 'store'])->name('pagos.store');
Route::post('/pagos/liquidate-debt', [PaymentController::class, 'liquidateDebt'])->name('pagos.liquidateDebt');
Route::get('/api/payments/client-debt', [PaymentController::class, 'searchClientDebt'])->name('api.payments.clientDebt');
Route::get('/api/payments/group-debt', [PaymentController::class, 'searchGroupDebt'])->name('api.payments.groupDebt');
Route::get('/api/payments/client-history/{client}', [PaymentController::class, 'clientHistory'])->name('api.payments.clientHistory');

// Cierres de Caja
Route::get('/cash-shifts/excel', [CashShiftController::class, 'excel'])->name('cash-shifts.excel');
Route::get('/cash-shifts', [CashShiftController::class, 'index'])->name('cash-shifts.index');
Route::get('/cash-shifts/create', [CashShiftController::class, 'create'])->name('cash-shifts.create');
Route::post('/cash-shifts', [CashShiftController::class, 'store'])->name('cash-shifts.store');
Route::get('/cash-shifts/{cashShift}/pdf', [CashShiftController::class, 'pdf'])->name('cash-shifts.pdf');

Route::get('/medidas', function () {
    return view('medidas');
})->name('medidas');

Route::get('/nutricion', function () {
    return view('nutricion');
})->name('nutricion');

Route::get('/asistencia', function () {
    return view('asistencia');
})->name('asistencia');

Route::get('/reportes', function () {
    return view('reportes');
})->name('reportes');

// Grupos
Route::get('/grupos', [GroupController::class, 'index'])->name('grupos.index');
Route::get('/grupos/{name}', [GroupController::class, 'show'])->name('grupos.show');
Route::get('/grupos/{name}/edit', [GroupController::class, 'edit'])->name('grupos.edit');
Route::put('/grupos/{name}', [GroupController::class, 'update'])->name('grupos.update');

