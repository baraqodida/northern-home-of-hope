<?php

namespace App\Http\Controllers;

use App\Models\Contribution;
use App\Models\User;
use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class ContributionController extends Controller
{
    private function getFilteredQuery(Request $request)
    {
        $user = Auth::user();
        $query = Contribution::with(['user.group']);

        if ($user->isLeader()) {
            $query->whereHas('user', fn($q) => $q->where('group_id', $user->group_id));
        } elseif ($user->isAdmin() && $request->filled('group_id')) {
            $query->whereHas('user', fn($q) => $q->where('group_id', $request->group_id));
        } elseif (!$user->isAdmin() && !$user->isLeader()) {
            $query->where('user_id', $user->id);
        }

        if ($request->filled('sub_county')) {
            $query->whereHas('user', fn($q) => $q->where('sub_county', $request->sub_county));
        }

        return $query;
    }

    public function index(Request $request)
    {
        $contributionQuery = $this->getFilteredQuery($request);
        
        $memberCountQuery = User::where('role', '!=', 'admin');
        if (Auth::user()->isLeader()) $memberCountQuery->where('group_id', Auth::user()->group_id);
        if (Auth::user()->isAdmin() && $request->filled('group_id')) $memberCountQuery->where('group_id', $request->group_id);
        $totalMembers = $memberCountQuery->count();

        $totalCollected = (clone $contributionQuery)->where('status', 'completed')->sum('amount');
        
        $contributions = $contributionQuery->join('users', 'contributions.user_id', '=', 'users.id')
            ->leftJoin('groups', 'users.group_id', '=', 'groups.id')
            ->select('contributions.*')
            ->orderBy('groups.name', 'asc')
            ->orderBy('users.name', 'asc')
            ->paginate(15)
            ->withQueryString();

        $allGroups = Group::orderBy('name', 'asc')->get();
        $allSubCounties = User::whereNotNull('sub_county')->where('sub_county', '!=', '')->distinct()->pluck('sub_county');
        $allUsers = Auth::user()->isAdmin() ? User::where('role', '!=', 'admin')->get() : 
                    (Auth::user()->isLeader() ? User::where('group_id', Auth::user()->group_id)->get() : collect());

        return view('dashboard', compact('contributions', 'totalCollected', 'totalMembers', 'allGroups', 'allSubCounties', 'allUsers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id'    => 'required|exists:users,id',
            'weeks_paid' => 'required|integer|min:1',
            'status'     => 'required|in:completed,pending',
        ]);

        $calculatedAmount = $validated['weeks_paid'] * 100;

        Contribution::updateOrCreate(
            ['user_id' => $validated['user_id'], 'purpose' => 'Weekly Payment'],
            ['amount' => DB::raw('amount + ' . $calculatedAmount), 'status' => $validated['status']]
        );

        return redirect()->back()->with('success', 'Payment recorded successfully!');
    }

    public function exportPdf(Request $request)
    {
        $contributions = $this->getFilteredQuery($request)
            ->join('users', 'contributions.user_id', '=', 'users.id')
            ->leftJoin('groups', 'users.group_id', '=', 'groups.id')
            ->select('contributions.*')
            ->orderBy('groups.name', 'asc')
            ->orderBy('users.name', 'asc')
            ->get();

        $pdf = Pdf::loadView('exports.ledger', ['contributions' => $contributions]);
        return $pdf->download('Northern_Home_Ledger_' . now()->format('Y-m-d') . '.pdf');
    }
}