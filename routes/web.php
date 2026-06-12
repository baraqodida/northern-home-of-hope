<?php

use App\Http\Controllers\ContributionController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MemberController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes Shield & Administrative Gateways
|--------------------------------------------------------------------------
*/

// 1. Guest Authentication Routes Gateway
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'authenticate'])->name('login.authenticate');
});

// 2. Protected Structural Administrative Session Workspace
Route::middleware('auth')->group(function () {
    
    Route::get('/', function () {
        return redirect()->route('dashboard');
    });

    // Dashboard & Contribution Routes
    Route::get('/dashboard', [ContributionController::class, 'index'])->name('dashboard');
    
    // Contributions
    Route::post('/contributions', [ContributionController::class, 'store'])->name('contributions.store');
    
    // Member Registration & Removal
    Route::post('/members/store', [MemberController::class, 'storeMember'])->name('members.store');
    Route::delete('/members/{id}', [MemberController::class, 'destroy'])->name('members.destroy');
    
    // --- UPDATED: Member Import Routes ---
    Route::get('/imports', [MemberController::class, 'showImportForm'])->name('members.import.form');
    Route::post('/imports', [MemberController::class, 'importMembers'])->name('members.import.submit');
    
    // Export Routes
    Route::get('/export/pdf', [ContributionController::class, 'exportPdf'])->name('export.pdf');
    Route::get('/export/excel', [ContributionController::class, 'exportExcel'])->name('export.excel');
    
    // --- TEMPORARY CLEANUP ROUTE ---
    Route::get('/cleanup-contributions', function () {
        if (!Auth::user()->isAdmin()) abort(403);
        $users = DB::table('contributions')->select('user_id')->distinct()->get();
        foreach ($users as $u) {
            $total = DB::table('contributions')->where('user_id', $u->user_id)->sum('amount');
            DB::table('contributions')->where('user_id', $u->user_id)->delete();
            DB::table('contributions')->insert([
                'user_id' => $u->user_id, 'purpose' => 'Weekly Payment', 'amount' => $total, 
                'status' => 'completed', 'created_at' => now(), 'updated_at' => now()
            ]);
        }
        return "Cleanup complete! All funds merged into 'Weekly Payment'.";
    });

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});