<?php

use App\Http\Controllers\Admin\AuditLogController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\EmployeeScheduleController;
use App\Http\Controllers\Admin\ExpenseController;
use App\Http\Controllers\Admin\InventoryController;
use App\Http\Controllers\Admin\InvoiceController;
use App\Http\Controllers\Admin\MaintenanceController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\ReservationController;
use App\Http\Controllers\Admin\RestaurantController;
use App\Http\Controllers\Admin\RestaurantMenuController;
use App\Http\Controllers\Admin\RestaurantOrderController;
use App\Http\Controllers\Admin\RoomController;
use App\Http\Controllers\Admin\RoomTypeController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Guest\GuestReservationController;
use App\Models\Room;
use App\Models\RoomType;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    try {
        $roomTypes = RoomType::orderBy('name')->get();
        $featuredRooms = Room::with('roomType')
            ->whereIn('status', ['available', 'occupied'])
            ->latest('id')
            ->take(4)
            ->get();
        $databaseUnavailable = false;
    } catch (QueryException $e) {
        $roomTypes = collect();
        $featuredRooms = collect();
        $databaseUnavailable = true;
    }

    return view('welcome', compact('roomTypes', 'featuredRooms', 'databaseUnavailable'));
})->name('home');

Route::get('/visiter', function () {
    try {
        $roomTypes = RoomType::orderBy('name')->get();
        $featuredRooms = Room::with('roomType')
            ->whereIn('status', ['available', 'occupied'])
            ->latest('id')
            ->take(6)
            ->get();
        $databaseUnavailable = false;
    } catch (QueryException $e) {
        $roomTypes = collect();
        $featuredRooms = collect();
        $databaseUnavailable = true;
    }

    return view('visitor.portal', compact('roomTypes', 'featuredRooms', 'databaseUnavailable'));
})->name('visitor.portal');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/password/reset', [AuthController::class, 'showReset'])->name('password.request');
Route::post('/password/email', [AuthController::class, 'sendReset'])->name('password.email');
Route::get('/password/reset/{token}', [AuthController::class, 'showNewPassword'])->name('password.reset');
Route::post('/password/reset', [AuthController::class, 'resetPassword'])->name('password.update');

Route::prefix('reservation')->name('guest.')->group(function () {
    // Réservation en 1 étape (page unique)
    Route::get('/', [GuestReservationController::class, 'oneStep'])->name('step1');
    Route::post('/book', [GuestReservationController::class, 'book'])->name('book');

    // Ancien parcours multi-étapes (conservé si besoin)
    Route::post('/search', [GuestReservationController::class, 'search'])->name('search');
    Route::get('/details', [GuestReservationController::class, 'step2'])->name('step2');
    Route::post('/confirm', [GuestReservationController::class, 'confirm'])->name('confirm');
    Route::get('/pay/{token}', [GuestReservationController::class, 'payment'])->name('payment');
    Route::post('/pay/{token}', [GuestReservationController::class, 'processPayment'])->name('payment.process');
    Route::get('/success/{token}', [GuestReservationController::class, 'success'])->name('success');
    Route::get('/cancel', [GuestReservationController::class, 'showCancel'])->name('cancel');
    Route::post('/cancel', [GuestReservationController::class, 'processCancel'])->name('cancel.process');
});

Route::middleware(['auth', 'log.activity'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::middleware('role:manager|admin|receptionist')->group(function () {
        Route::resource('rooms', RoomController::class);
        Route::patch('rooms/{room}/status', [RoomController::class, 'updateStatus'])->name('rooms.update-status');

        Route::resource('reservations', ReservationController::class);
        Route::post('reservations/{id}/checkin', [ReservationController::class, 'checkIn'])->name('reservations.checkin');
        Route::post('reservations/{id}/checkout', [ReservationController::class, 'checkOut'])->name('reservations.checkout');
        Route::get('calendar', [ReservationController::class, 'calendar'])->name('reservations.calendar');

        Route::resource('customers', CustomerController::class)->only(['index', 'store', 'update', 'destroy']);

        Route::get('restaurant', [RestaurantController::class, 'index'])->name('restaurant.index');
        Route::get('restaurant/create', [RestaurantController::class, 'create'])->name('restaurant.create');
        Route::post('restaurant', [RestaurantController::class, 'store'])->name('restaurant.store');
        Route::patch('restaurant/{restaurant}/cancel', [RestaurantController::class, 'cancel'])->name('restaurant.cancel');
        Route::get('restaurant/monthly', [RestaurantController::class, 'monthly'])->name('restaurant.monthly');
        Route::resource('restaurant-menu', RestaurantMenuController::class)->only(['index', 'store', 'update', 'destroy']);
        Route::resource('restaurant-orders', RestaurantOrderController::class)->only(['index', 'store']);
        Route::patch('restaurant-orders/{restaurantOrder}/status', [RestaurantOrderController::class, 'updateStatus'])
            ->name('restaurant-orders.update-status');
    });

    Route::middleware('role:manager|admin')->group(function () {
        Route::resource('room-types', RoomTypeController::class)->except(['show']);

        Route::resource('payments', PaymentController::class)->except(['edit', 'update', 'destroy']);
        Route::patch('payments/{payment}/refund', [PaymentController::class, 'refund'])->name('payments.refund');
        Route::post('api/stripe-intent', [PaymentController::class, 'createStripeIntent'])->name('stripe.intent');

        Route::resource('invoices', InvoiceController::class)->only(['index', 'show']);
        Route::get('invoices/{reservation}/generate', [InvoiceController::class, 'generate'])->name('invoices.generate');
        Route::get('invoices/{invoice}/download', [InvoiceController::class, 'download'])->name('invoices.download');
        Route::patch('invoices/{invoice}/cancel', [InvoiceController::class, 'cancel'])->name('invoices.cancel');

        Route::resource('maintenance', MaintenanceController::class);

        Route::get('inventory', [InventoryController::class, 'index'])->name('inventory.index');
        Route::post('inventory', [InventoryController::class, 'store'])->name('inventory.store');
        Route::post('inventory/movement', [InventoryController::class, 'movement'])->name('inventory.movement');

        Route::resource('expenses', ExpenseController::class);

        Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('reports/export', [ReportController::class, 'export'])->name('reports.export');

        Route::resource('users', UserController::class)->except(['show']);
        Route::patch('users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');

        Route::get('audit-logs', [AuditLogController::class, 'index'])->name('audit-logs.index');
        Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
        Route::put('settings', [SettingController::class, 'update'])->name('settings.update');
    });

    Route::middleware('role:manager|admin|hr')->group(function () {
        Route::resource('employees', EmployeeController::class);
        Route::get('employee-schedule', [EmployeeScheduleController::class, 'index'])->name('employee-schedule.index');
        Route::post('employee-schedule/shifts', [EmployeeScheduleController::class, 'storeShift'])->name('employee-schedule.shifts.store');
        Route::post('employee-schedule/tasks', [EmployeeScheduleController::class, 'storeTask'])->name('employee-schedule.tasks.store');
        Route::patch('employee-schedule/tasks/{task}', [EmployeeScheduleController::class, 'updateTask'])->name('employee-schedule.tasks.update');
    });
});
