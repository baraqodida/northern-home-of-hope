<?php

namespace App\Http\Controllers;

use App\Models\Contribution;
use App\Models\ContributionHistory;
use App\Models\User;
use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class ContributionController extends Controller
{
    private function getFilteredUserQuery(Request $request)
    {
        $user = Auth::user();
        $query = User::where('role', '!=', 'admin');

        if ($user->isLeader()) {
            $query->where('group_id', $user->group_id);
        } elseif ($user->isAdmin() && $request->filled('group_id')) {
            $query->where('group_id', $request->group_id);
        }

        if ($request->filled('sub_county')) {
            $query->where('sub_county', $request->sub_county);
        }

        // ADDED: Search logic to the helper
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        return $query;
    }

    public function index(Request $request)
    {
        $selectedYear = $request->input('year', now()->year);
        $selectedMonth = $request->input('month', now()->month);
        $selectedWeek = $request->input('week_number', 1);
        
        $query = $this->getFilteredUserQuery($request);
        $totalMembers = User::where('role', '!=', 'admin')->count();
        
        // Dynamically calculate total based on applied filters
        $totalCollected = ContributionHistory::where('year', $selectedYear)
            ->where('month', $selectedMonth)
            ->where('week_label', 'Week ' . $selectedWeek)
            ->whereHas('contribution.user', function($q) use ($request) {
                $user = Auth::user();
                if ($user->isLeader()) {
                    $q->where('group_id', $user->group_id);
                } elseif ($user->isAdmin() && $request->filled('group_id')) {
                    $q->where('group_id', $request->group_id);
                }
                if ($request->filled('sub_county')) {
                    $q->where('sub_county', $request->sub_county);
                }
                // ADDED: Ensure search also applies to total calculation if desired
                if ($request->filled('search')) {
                    $q->where('name', 'like', '%' . $request->search . '%');
                }
            })
            ->sum('amount');
        
        $members = $query->with(['group', 'contribution'])
                         ->orderBy('name', 'asc')
                         ->paginate(15)
                         ->withQueryString();

        $allGroups = Group::orderBy('name', 'asc')->get();
        $allSubCounties = User::whereNotNull('sub_county')->where('sub_county', '!=', '')->distinct()->pluck('sub_county');
        $allMembers = User::where('role', '!=', 'admin')->orderBy('name', 'asc')->get();
        $contributionHistories = ContributionHistory::with('contribution.user')->latest()->limit(15)->get();

        return view('dashboard', compact(
            'members', 'totalCollected', 'totalMembers', 'allGroups', 'allSubCounties', 'allMembers', 'contributionHistories'
        ));
    }

    public function exportPdf(Request $request)
    {
        $query = $this->getFilteredUserQuery($request);
        $members = $query->with(['group', 'contribution'])
                         ->orderBy('group_id', 'asc')
                         ->orderBy('name', 'asc')
                         ->get();
        
        $pdf = Pdf::loadView('exports.ledger', compact('members'));
        
        return $pdf->download('Northern_Home_Ledger_' . now()->format('Y-m-d') . '.pdf');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id'     => 'required|exists:users,id',
            'amount'      => 'required|numeric|min:1',
            'year'        => 'required|integer',
            'month'       => 'required|integer',
            'week_number' => 'required|integer|between:1,4',
            'status'      => 'required|in:completed,pending',
        ]);

        DB::transaction(function () use ($validated) {
            $contribution = Contribution::updateOrCreate(
                ['user_id' => $validated['user_id'], 'purpose' => 'Weekly Payment'],
                ['amount' => DB::raw('amount + ' . $validated['amount']), 'status' => $validated['status']]
            );

            ContributionHistory::create([
                'contribution_id' => $contribution->id,
                'amount'          => $validated['amount'],
                'week_label'      => 'Week ' . $validated['week_number'],
                'month'           => $validated['month'],
                'year'            => $validated['year'],
            ]);
        });

        return redirect()->back()->with('success', 'Payment recorded successfully.');
    }
}