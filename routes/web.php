<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\ArticleControler;
use App\Http\Controllers\CategoryControler;
use App\Http\Controllers\NewsContrller;
use App\Models\Upload;
use Illuminate\Support\Facades\Route;
use App\Models\ArticleCategory;
use App\Models\Article;

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

    $article_categories =ArticleCategory::all();
    $articles =Article::paginate(10);

    return view('Webpage.welcome', compact('data', 'slug','article_categories','articles'));
});
Route::get('/video', function () {
    $data = Upload::whereIn('type', array('video','bulkvideo'))->paginate(10);
    $slug = 'video';
    return view('Webpage.welcome', compact('data', 'slug'));
});
Route::get('/audio', function () {
    $data = Upload::whereIn('type', array('audio','bulkaudio'))->paginate(10);
    $slug = 'audio';
    return view('Webpage.welcome', compact('data', 'slug'));
});

Route::get('/news/category/{id}', [NewsContrller::class, 'category'])->name('new_category');
Route::get('/news/detail/{id}', [NewsContrller::class, 'detail'])->name('new_detail');

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

    Route::post('/addBulkFile', [UploadController::class, 'addBulkFile'])->name('addBulkFile');
    Route::post('/editBulkFile', [UploadController::class, 'editBulkFile'])->name('editBulkFile');
    Route::get('/deleteBulkUpload/{id}', [UploadController::class, 'deleteBulkUpload'])->name('deleteBulkUpload');

    Route::get('/article', [ArticleControler::class, 'index'])->name('article');
    Route::get('/article/add', [ArticleControler::class, 'add'])->name('article_add');
    Route::get('/article/edit/{id}', [ArticleControler::class, 'edit'])->name('article_edit');
    Route::post('/article/update', [ArticleControler::class, 'update'])->name('article_update');
    Route::get('/article/delete/{id}', [ArticleControler::class, 'delete'])->name('article_delete');

    Route::get('/article/category', [CategoryControler::class, 'index'])->name('category');
    Route::post('/article/category/update', [CategoryControler::class, 'update'])->name('category_update');
    Route::get('/article/category/delete/{id}', [CategoryControler::class, 'delete'])->name('category_delete');


    Route::get('/settings', [AdminController::class, 'settings'])->name('settings');
    Route::post('/updateSetting', [AdminController::class, 'updateSetting'])->name('updateSetting');

    Route::any('/ckfinder/connector', '\CKSource\CKFinderBridge\Controller\CKFinderController@requestAction')
        ->name('ckfinder_connector');

    Route::any('/ckfinder/browser', '\CKSource\CKFinderBridge\Controller\CKFinderController@browserAction')
        ->name('ckfinder_browser');

});
