<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\UploadController;
use App\Models\Upload;
use Illuminate\Support\Facades\Route;

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
    $data = Upload::paginate(10);
    $slug = 'All';
    return view('Webpage.welcome', compact('data', 'slug'));
});
Route::get('/video', function () {
    $data = Upload::where('type', 'video')->paginate(10);
    $slug = 'video';
    return view('Webpage.welcome', compact('data', 'slug'));
});
Route::get('/audio', function () {
    $data = Upload::where('type', 'audio')->paginate(10);
    $slug = 'audio';
    return view('Webpage.welcome', compact('data', 'slug'));
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::get('/deleteUser/{id}', [AdminController::class, 'deleteUser'])->name('deleteUser');
    Route::post('/addUser', [AdminController::class, 'addUser'])->name('addUser');

    // Files Upload
    Route::get('/upload', [UploadController::class, 'index'])->name('upload');
    Route::get('/upload/{slug}', [UploadController::class, 'slug'])->name('slug');
    Route::get('/deleteUpload/{id}', [UploadController::class, 'deleteUpload'])->name('deleteUpload');
    Route::post('/addFile', [UploadController::class, 'addFile'])->name('addFile');
    Route::post('/editFile', [UploadController::class, 'editFile'])->name('editFile');
});
