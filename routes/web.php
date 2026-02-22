<?php

use App\Http\Controllers\AuthManager;
use App\Http\Controllers\BookController;
use App\Http\Controllers\FilterController;
use App\Models\Book;
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
    $books = [];
    if (auth()->check()) {
        $books = Book::all(); // Με την εντολη all() ολοι οι user μπορουν να βλεπουν ολα τα posts οχι μονο τα δικα τους
        // $posts = Post::where('user_id', auth()->id())->get(); Με αυτην την εντολη καθε user θα μπορουσε να δει μονο τα δικα του post
    }
    return view('welcome', ['books' => $books]);
})->name('home');

Route::get('/login', [AuthManager::class, 'login'])->name('login'); // οταν ενας χρηστης επικσεφτει την σελιδα first-app/login η laravel θα χρησιμοποιησει αυτο το route 
                                                                    // Οταν γινει αυτο τρεχει το login() μεθοδο μεσα στον controller AuthManager
                                                                    // το ->name('login') δινει ονομα σε αυτο το route το login ωστε να μπορουμε να αναφερομαστε σε αυτο με το ονομα του γρηγοροτερα
Route::post('/login', [AuthManager::class, 'loginPost'])->name('login.post');


Route::get('/registration', [AuthManager::class, 'registration'])->name('registration');
Route::post('/registration', [AuthManager::class, 'registrationPost'])->name('registration.post');
Route::get('/logout', [AuthManager::class, 'logout'])->name('logout');
// ο AuthManager ειναι ο controller υπευθυνος για το authentication 

//Route::get()
//You want to retrieve or display data

// You are showing a page

// No data is being changed

//Route::post()
// You are submitting form data

// You are creating or modifying data

// Sensitive data is being sent (like passwords)

// ο PostController θα ειναι ο controller υπευθυνος για τα post

// Blog post related routes

Route::post('/create-book', [BookController::class, 'createBook']);
Route::get('/edit-book/{book}', [BookController::class, 'showEditScreen']);
Route::put('/edit-book/{book}', [BookController::class, 'actuallyUpdateBook']);
Route::delete('/delete-book/{book}', [BookController::class, 'deleteBook']);
Route::get('/', [BookController::class, 'showCreateScreen'])->name('home');
Route::get('/search', [BookController::class, 'searchBook']);
Route::get('/', [BookController::class, 'categoryFilter'])->name('books-index');
Route::get('/sort-books', [BookController::class, 'sortBooks']);