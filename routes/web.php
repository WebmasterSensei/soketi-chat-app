<?php

use App\Http\Controllers\ChatController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use App\Events\OrderStatusUpdate;
use Inertia\Inertia;
use App\Models\Group;
use App\Events\PresenceEvent;
use App\Events\PrivateEvent;
use App\Http\Controllers\NotificationController;
use App\Events\NewEvent;
// use App\Events\OrderStatusUpdate;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});



Route::get('/chat-area/{id}', [ChatController::class, 'index'])->name('chat-area');
Route::get('/get_users_chats', [ChatController::class, 'get_users'])->name('users-get');
Route::post('/chat-store', [ChatController::class, 'store'])->name('chats.store');



Route::get('/get_users', [NotificationController::class, 'get_notifications'])->name('notifications');
Route::post('/notif', [NotificationController::class, 'store'])->name('notif.store');


require __DIR__.'/auth.php';
