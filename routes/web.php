<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $popularBooks = App\Models\Book::with('category')
        ->orderBy('rating', 'desc')
        ->take(8)
        ->get();
    return view('landing', compact('popularBooks'));
});

// Public Catalog Route
Route::get('/catalog', [App\Http\Controllers\BookController::class, 'catalog'])->name('catalog');

// Auth Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Admin Routes
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        if (auth()->user()->role !== 'admin') abort(403);
        
        $totalBooks = App\Models\Book::count();
        $totalMembers = App\Models\User::where('role', 'user')->count();
        $borrowedBooks = App\Models\Loan::where('status', 'approved')->count();
        $availableBooks = App\Models\Book::sum('available');
        $popularBooks = App\Models\Book::with('category')->orderBy('rating', 'desc')->take(5)->get();
        $recentActivities = App\Models\Loan::with(['user', 'book'])
            ->orderBy('created_at', 'desc')->take(5)->get();
        
        return view('dashboard.admin', compact('totalBooks', 'totalMembers', 'borrowedBooks', 'availableBooks', 'popularBooks', 'recentActivities'));
    })->name('dashboard');
    
    Route::get('/books', [App\Http\Controllers\BookController::class, 'index'])->name('books');
    Route::post('/books', [App\Http\Controllers\BookController::class, 'store'])->name('books.store');
    Route::put('/books/{id}', [App\Http\Controllers\BookController::class, 'update'])->name('books.update');
    Route::delete('/books/{id}', [App\Http\Controllers\BookController::class, 'destroy'])->name('books.destroy');
    
    Route::get('/categories', [App\Http\Controllers\CategoryController::class, 'index'])->name('categories');
    Route::post('/categories', [App\Http\Controllers\CategoryController::class, 'store'])->name('categories.store');
    Route::put('/categories/{id}', [App\Http\Controllers\CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{id}', [App\Http\Controllers\CategoryController::class, 'destroy'])->name('categories.destroy');
    
    Route::get('/officers', [App\Http\Controllers\UserController::class, 'indexOfficers'])->name('officers');
    Route::post('/officers', [App\Http\Controllers\UserController::class, 'storeOfficer'])->name('officers.store');
    Route::put('/officers/{id}', [App\Http\Controllers\UserController::class, 'updateOfficer'])->name('officers.update');
    Route::delete('/officers/{id}', [App\Http\Controllers\UserController::class, 'destroyOfficer'])->name('officers.destroy');
    
    Route::get('/users', [App\Http\Controllers\UserController::class, 'indexUsers'])->name('users');
    Route::delete('/users/{id}', [App\Http\Controllers\UserController::class, 'destroyUser'])->name('users.destroy');
    
    Route::get('/loans', [App\Http\Controllers\LoanController::class, 'index'])->name('loans');
    Route::post('/loans/{id}/approve', [App\Http\Controllers\LoanController::class, 'approve'])->name('loans.approve');
    Route::post('/loans/{id}/reject', [App\Http\Controllers\LoanController::class, 'reject'])->name('loans.reject');
    Route::post('/loans/{id}/approve-return', [App\Http\Controllers\LoanController::class, 'approveReturn'])->name('loans.approve-return');
    Route::post('/loans/{id}/reject-return', [App\Http\Controllers\LoanController::class, 'rejectReturn'])->name('loans.reject-return');
    
    Route::get('/reviews', [App\Http\Controllers\ReviewController::class, 'index'])->name('reviews');
    Route::delete('/reviews/{id}', [App\Http\Controllers\ReviewController::class, 'destroy'])->name('reviews.destroy');
    
    Route::get('/reports', function () {
        if (auth()->user()->role !== 'admin') abort(403);
        return view('admin.reports');
    })->name('reports');
    
    Route::get('/reports/books', [App\Http\Controllers\ReportController::class, 'books'])->name('reports.books');
    Route::get('/reports/loans', [App\Http\Controllers\ReportController::class, 'loans'])->name('reports.loans');
    Route::get('/reports/returns', [App\Http\Controllers\ReportController::class, 'returns'])->name('reports.returns');
    Route::get('/reports/users', [App\Http\Controllers\ReportController::class, 'users'])->name('reports.users');
});

// Petugas Routes
Route::middleware(['auth'])->prefix('petugas')->name('petugas.')->group(function () {
    Route::get('/dashboard', function () {
        if (auth()->user()->role !== 'petugas') abort(403);
        
        $borrowedBooks = App\Models\Loan::where('status', 'approved')->count();
        $returnsToday = App\Models\Loan::where('status', 'returned')->whereDate('return_date', today())->count();
        $loansToday = App\Models\Loan::whereDate('created_at', today())->count();
        $overdueBooks = App\Models\Loan::where('status', 'approved')->where('due_date', '<', today())->count();
        $todayLoans = App\Models\Loan::with(['user', 'book'])->whereDate('created_at', today())->take(4)->get();
        $recentLoans = App\Models\Loan::with(['user', 'book'])->orderBy('created_at', 'desc')->take(4)->get();
        
        return view('dashboard.petugas', compact('borrowedBooks', 'returnsToday', 'loansToday', 'overdueBooks', 'todayLoans', 'recentLoans'));
    })->name('dashboard');
    
    Route::get('/books', [App\Http\Controllers\BookController::class, 'index'])->name('books');
    Route::post('/books', [App\Http\Controllers\BookController::class, 'store'])->name('books.store');
    Route::put('/books/{id}', [App\Http\Controllers\BookController::class, 'update'])->name('books.update');
    Route::delete('/books/{id}', [App\Http\Controllers\BookController::class, 'destroy'])->name('books.destroy');
    
    Route::get('/categories', [App\Http\Controllers\CategoryController::class, 'index'])->name('categories');
    Route::post('/categories', [App\Http\Controllers\CategoryController::class, 'store'])->name('categories.store');
    Route::put('/categories/{id}', [App\Http\Controllers\CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{id}', [App\Http\Controllers\CategoryController::class, 'destroy'])->name('categories.destroy');
    
    Route::get('/loans', [App\Http\Controllers\LoanController::class, 'index'])->name('loans');
    Route::post('/loans/{id}/approve', [App\Http\Controllers\LoanController::class, 'approve'])->name('loans.approve');
    Route::post('/loans/{id}/reject', [App\Http\Controllers\LoanController::class, 'reject'])->name('loans.reject');
    Route::post('/loans/{id}/approve-return', [App\Http\Controllers\LoanController::class, 'approveReturn'])->name('loans.approve-return');
    Route::post('/loans/{id}/reject-return', [App\Http\Controllers\LoanController::class, 'rejectReturn'])->name('loans.reject-return');
    
    Route::get('/reviews', [App\Http\Controllers\ReviewController::class, 'index'])->name('reviews');
    Route::delete('/reviews/{id}', [App\Http\Controllers\ReviewController::class, 'destroy'])->name('reviews.destroy');
    
    Route::get('/reports', function () {
        if (auth()->user()->role !== 'petugas') abort(403);
        return view('petugas.reports');
    })->name('reports');
    
    Route::get('/reports/books', [App\Http\Controllers\ReportController::class, 'books'])->name('reports.books');
    Route::get('/reports/loans', [App\Http\Controllers\ReportController::class, 'loans'])->name('reports.loans');
    Route::get('/reports/returns', [App\Http\Controllers\ReportController::class, 'returns'])->name('reports.returns');
});

// User Routes
Route::middleware(['auth'])->prefix('user')->name('user.')->group(function () {
    Route::get('/dashboard', function () {
        if (auth()->user()->role !== 'user') abort(403);
        
        $recommendedBooks = App\Models\Book::with('category')->orderBy('rating', 'desc')->take(4)->get();
        $currentLoans = App\Models\Loan::with(['book.category'])
            ->where('user_id', auth()->id())
            ->where('status', 'approved')
            ->take(2)
            ->get();
        $totalBorrowed = App\Models\Loan::where('user_id', auth()->id())->count();
        $activeLoan = App\Models\Loan::where('user_id', auth()->id())->where('status', 'approved')->count();
        $totalReturned = App\Models\Loan::where('user_id', auth()->id())->where('status', 'returned')->count();
        
        return view('dashboard.user', compact('recommendedBooks', 'currentLoans', 'totalBorrowed', 'activeLoan', 'totalReturned'));
    })->name('dashboard');
    
    Route::get('/catalog', [App\Http\Controllers\BookController::class, 'catalog'])->name('catalog');
    Route::get('/book/{id}', [App\Http\Controllers\BookController::class, 'show'])->name('book.show');
    
    // Peminjaman
    Route::get('/loan/create/{book}', [App\Http\Controllers\LoanController::class, 'create'])->name('loan.create');
    Route::post('/loan/store', [App\Http\Controllers\LoanController::class, 'store'])->name('loan.store');
    Route::get('/loan/receipt/{id}', [App\Http\Controllers\LoanController::class, 'receipt'])->name('loan.receipt');
    
    // Pengembalian
    Route::get('/return/{id}', [App\Http\Controllers\LoanController::class, 'returnRequest'])->name('return.request');
    Route::post('/return/{id}', [App\Http\Controllers\LoanController::class, 'submitReturn'])->name('return.submit');
    
    Route::get('/loans', function () {
        if (auth()->user()->role !== 'user') abort(403);
        $loans = App\Models\Loan::with(['book.category'])
            ->where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();
        return view('user.loans', compact('loans'));
    })->name('loans');
    
    Route::get('/history', function () {
        if (auth()->user()->role !== 'user') abort(403);
        $loans = App\Models\Loan::with(['book.category', 'review'])
            ->where('user_id', auth()->id())
            ->where('status', 'returned')
            ->orderBy('created_at', 'desc')
            ->get();
        return view('user.history', compact('loans'));
    })->name('history');
    
    Route::post('/review', [App\Http\Controllers\ReviewController::class, 'store'])->name('review.store');
});

