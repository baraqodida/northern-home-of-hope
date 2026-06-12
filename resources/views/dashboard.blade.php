<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Northern Home of Hope Portal</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-gray-100 font-sans antialiased">

    <nav class="bg-[#1A365D] text-white px-6 py-4 shadow-md">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <h1 class="text-xl font-bold tracking-wide">Northern Home of Hope Portal</h1>
            <span class="text-sm bg-[#2B6CB0] px-3 py-1 rounded-full font-medium">Admin Dashboard</span>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-800 rounded-xl text-sm font-medium flex items-center justify-between shadow-sm">
                <div class="flex items-center gap-2">🚀 <span>{{ session('success') }}</span></div>
                <button onclick="this.parentElement.remove()" class="text-green-600 hover:text-green-800 font-bold text-lg cursor-pointer">&times;</button>
            </div>
        @endif

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h5 class="text-sm font-medium text-gray-500 uppercase tracking-wider">System Integrity Status</h5>
                    <p class="text-gray-900 mt-1">Total Verified Members: <strong>{{ $totalMembers }}</strong></p>
                </div>
                <div>
                    @if($totalMembers != 163)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-800">⚠️ ALERT: Data inconsistency</span>
                    @else
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-800">✅ Data Healthy</span>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <form action="{{ url('/dashboard') }}" method="GET" class="w-full">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Funds Collected For</p>
                            <div class="flex gap-2 mt-1">
                                <select name="year" onchange="this.form.submit()" class="font-bold text-gray-700 bg-transparent border-none focus:ring-0 p-0 cursor-pointer">
                                    @for($y = 2026; $y <= 2030; $y++) <option value="{{ $y }}" {{ request('year', now()->year) == $y ? 'selected' : '' }}>{{ $y }}</option> @endfor
                                </select>
                                <select name="month" onchange="this.form.submit()" class="font-bold text-gray-700 bg-transparent border-none focus:ring-0 p-0 cursor-pointer">
                                    @foreach(['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'] as $i => $m) <option value="{{ $i + 1 }}" {{ request('month', now()->month) == ($i + 1) ? 'selected' : '' }}>{{ $m }}</option> @endforeach
                                </select>
                                <select name="week_number" onchange="this.form.submit()" class="font-bold text-gray-900 bg-transparent border-none focus:ring-0 p-0 cursor-pointer">
                                    @for($i = 1; $i <= 4; $i++) <option value="{{ $i }}" {{ request('week_number', 1) == $i ? 'selected' : '' }}>Wk {{ $i }}</option> @endfor
                                </select>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Total</p>
                            <h3 class="text-3xl font-bold text-green-600 mt-1">Ksh {{ number_format($totalCollected, 2) }}</h3>
                        </div>
                    </div>
                </form>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Active Registered Base</p>
                <h3 class="text-3xl font-bold text-gray-900 mt-1">{{ $totalMembers }} Members</h3>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                <h2 class="text-lg font-semibold text-gray-900">Member Structural Ledger</h2>
                <div class="flex items-center gap-2">
                    <form action="{{ url('/dashboard') }}" method="GET" class="flex items-center gap-2">
                        <input type="hidden" name="year" value="{{ request('year', now()->year) }}">
                        <input type="hidden" name="month" value="{{ request('month', now()->month) }}">
                        <input type="hidden" name="week_number" value="{{ request('week_number', 1) }}">
                        @if(request('group_id')) <input type="hidden" name="group_id" value="{{ request('group_id') }}"> @endif
                        @if(request('sub_county')) <input type="hidden" name="sub_county" value="{{ request('sub_county') }}"> @endif
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search members..." class="border rounded-lg px-3 py-1 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        <button type="submit" class="bg-gray-800 hover:bg-gray-900 text-white text-xs font-bold px-3 py-2 rounded-lg cursor-pointer">Find</button>
                    </form>
                    <a href="{{ route('export.pdf', request()->query()) }}" class="bg-red-600 hover:bg-red-700 text-white text-xs font-bold px-3 py-2 rounded-lg shadow-sm cursor-pointer flex items-center gap-1">📄 Export PDF</a>
                    <a href="{{ url('/imports') }}" class="bg-purple-600 hover:bg-purple-700 text-white text-xs font-bold px-3 py-2 rounded-lg shadow-sm cursor-pointer flex items-center gap-1">📁 Bulk Import</a>
                    <button onclick="document.getElementById('memberModal').classList.remove('hidden')" class="bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold px-3 py-2 rounded-lg shadow-sm cursor-pointer flex items-center gap-1">👤 Add Member</button>
                    <button onclick="document.getElementById('contributionModal').classList.remove('hidden')" class="bg-green-600 hover:bg-green-700 text-white text-xs font-bold px-3.5 py-2 rounded-lg shadow-sm cursor-pointer flex items-center gap-1.5">➕ Record Contribution</button>
                </div>
            </div>

            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                <form action="{{ url('/dashboard') }}" method="GET" class="flex flex-wrap items-end gap-4">
                    <input type="hidden" name="year" value="{{ request('year', now()->year) }}">
                    <input type="hidden" name="month" value="{{ request('month', now()->month) }}">
                    <input type="hidden" name="week_number" value="{{ request('week_number', 1) }}">
                    <input type="hidden" name="search" value="{{ request('search') }}">
                    
                    <div class="flex-1 min-w-[200px]">
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-600 mb-1.5">Filter By Group</label>
                        <select name="group_id" class="w-full bg-white border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                            <option value="">-- All Groups --</option>
                            @foreach($allGroups as $g) <option value="{{ $g->id }}" {{ request('group_id') == $g->id ? 'selected' : '' }}>{{ $g->name }}</option> @endforeach
                        </select>
                    </div>
                    <div class="flex-1 min-w-[200px]">
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-600 mb-1.5">Filter By Sub-County</label>
                        <select name="sub_county" class="w-full bg-white border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                            <option value="">-- All Sub-Counties --</option>
                            @foreach($allSubCounties as $sub) <option value="{{ $sub }}" {{ request('sub_county') == $sub ? 'selected' : '' }}>{{ $sub }}</option> @endforeach
                        </select>
                    </div>
                    <div class="flex gap-2">
                        <button type="submit" class="bg-[#2B6CB0] hover:bg-blue-700 text-white text-sm font-medium px-5 py-2 rounded-lg cursor-pointer">Apply</button>
                        <a href="{{ url('/dashboard') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 text-sm font-medium px-4 py-2 rounded-lg cursor-pointer flex items-center">Clear</a>
                    </div>
                </form>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-100 text-xs font-semibold uppercase tracking-wider text-gray-600 border-b border-gray-200">
                            <th class="px-6 py-3.5">Member Name</th>
                            <th class="px-6 py-3.5">Group</th>
                            <th class="px-6 py-3.5">Location</th>
                            <th class="px-6 py-3.5 text-right">Total Contributed</th>
                            <th class="px-6 py-3.5 text-center">Status</th>
                            <th class="px-6 py-3.5 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 text-sm text-gray-700">
                        @foreach($members as $member)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 font-medium text-gray-900">{{ $member->name }}</td>
                                <td class="px-6 py-4">{{ $member->group->name ?? 'Unassigned' }}</td>
                                <td class="px-6 py-4 text-xs">{{ $member->county }}, {{ $member->sub_county }}</td>
                                <td class="px-6 py-4 text-right font-bold text-green-700">Ksh {{ number_format($member->contribution->amount ?? 0, 2) }}</td>
                                <td class="px-6 py-4 text-center">
                                    @if(isset($member->contribution))
                                        <span class="px-2 py-1 text-[10px] font-bold uppercase rounded-full {{ $member->contribution->status == 'completed' ? 'bg-green-100 text-green-800' : 'bg-amber-100 text-amber-800' }}">
                                            {{ $member->contribution->status }}
                                        </span>
                                    @else
                                        <span class="text-gray-400 text-xs italic">N/A</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <form action="{{ route('members.destroy', $member->id) }}" method="POST" onsubmit="return confirm('WARNING: Are you sure you want to permanently remove {{ $member->name }}?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 font-bold text-xs bg-red-50 px-2 py-1 rounded border border-red-200 cursor-pointer">Remove</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">{{ $members->links() }}</div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mt-8">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-900">Recent Payment History</h2>
                <input type="text" id="historySearch" onkeyup="filterHistory()" placeholder="Search by name..." class="border rounded-lg px-3 py-1 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse" id="historyTable">
                    <thead>
                        <tr class="bg-gray-100 text-xs font-semibold uppercase tracking-wider text-gray-600">
                            <th class="px-6 py-3">Member</th>
                            <th class="px-6 py-3">Amount</th>
                            <th class="px-6 py-3">Status</th>
                            <th class="px-6 py-3 text-right">Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm">
                        @foreach($contributionHistories as $history)
                            <tr>
                                <td class="px-6 py-3 font-medium text-gray-900 name-cell">{{ $history->contribution->user->name ?? 'N/A' }}</td>
                                <td class="px-6 py-3 text-green-700 font-bold">Ksh {{ number_format($history->amount, 2) }}</td>
                                <td class="px-6 py-3">
                                     <span class="px-2 py-0.5 text-[10px] font-bold uppercase rounded-full {{ $history->contribution->status == 'completed' ? 'bg-green-100 text-green-800' : 'bg-amber-100 text-amber-800' }}">
                                        {{ $history->contribution->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-3 text-gray-500 text-right">{{ $history->created_at->format('M d, Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    {{-- Record Contribution Modal --}}
    <div id="contributionModal" class="hidden fixed inset-0 bg-gray-800/50 flex items-center justify-center p-4 z-50">
        <div class="bg-white rounded-xl p-6 w-full max-w-lg shadow-2xl max-h-[90vh] overflow-y-auto">
            <h2 class="text-xl font-bold mb-4">Record Contribution</h2>
            <form action="{{ route('contributions.store') }}" method="POST">
                @csrf
                <div class="grid grid-cols-3 gap-3 mb-4">
                    <div>
                        <label class="block text-xs font-medium mb-1">Year</label>
                        <select name="year" class="w-full border rounded-lg p-2 text-sm" required>
                            @for($y = 2026; $y <= 2030; $y++) <option value="{{ $y }}">{{ $y }}</option> @endfor
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium mb-1">Month</label>
                        <select name="month" class="w-full border rounded-lg p-2 text-sm" required>
                            @foreach(['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'] as $i => $m) <option value="{{ $i+1 }}">{{ $m }}</option> @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium mb-1">Week</label>
                        <select name="week_number" class="w-full border rounded-lg p-2 text-sm" required>
                            @for($i = 1; $i <= 4; $i++) <option value="{{ $i }}">Week {{ $i }}</option> @endfor
                        </select>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Select Group</label>
                    <select id="groupSelect" name="group_id" class="w-full border rounded-lg p-2 text-sm" onchange="filterMembers(this.value)" required>
                        <option value="">-- Choose Group --</option>
                        @foreach($allGroups as $g) <option value="{{ $g->id }}">{{ $g->name }}</option> @endforeach
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Select Member</label>
                    <select id="memberSelect" name="user_id" class="w-full border rounded-lg p-2 text-sm" required>
                        <option value="">-- Choose Member --</option>
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">Amount (Ksh)</label>
                        <input type="number" name="amount" class="w-full border rounded-lg p-2 text-sm" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Status</label>
                        <select name="status" class="w-full border rounded-lg p-2 text-sm" required>
                            <option value="completed">Completed</option>
                            <option value="pending">Pending</option>
                        </select>
                    </div>
                </div>
                <div class="flex justify-end gap-2 mt-6">
                    <button type="button" onclick="document.getElementById('contributionModal').classList.add('hidden')" class="px-4 py-2 text-gray-600 border rounded-lg">Cancel</button>
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg">Save Payment</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Register New Member Modal --}}
    <div id="memberModal" class="hidden fixed inset-0 bg-gray-800/50 flex items-center justify-center p-4 z-50">
        <div class="bg-white rounded-xl p-6 w-full max-w-md shadow-2xl">
            <h2 class="text-xl font-bold mb-4 text-gray-800">Register New Member</h2>
            <form action="{{ route('members.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                    <input type="text" name="name" class="w-full border border-gray-300 rounded-lg p-2 text-sm focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Group</label>
                    <select name="group_id" class="w-full border border-gray-300 rounded-lg p-2 text-sm focus:ring-2 focus:ring-blue-500" required>
                        <option value="">-- Select Group --</option>
                        @foreach($allGroups as $g) <option value="{{ $g->id }}">{{ $g->name }}</option> @endforeach
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">County</label>
                        <input type="text" name="county" class="w-full border border-gray-300 rounded-lg p-2 text-sm focus:ring-2 focus:ring-blue-500" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Sub-County</label>
                        <input type="text" name="sub_county" class="w-full border border-gray-300 rounded-lg p-2 text-sm focus:ring-2 focus:ring-blue-500" required>
                    </div>
                </div>
                <div class="flex justify-end gap-2 mt-6">
                    <button type="button" onclick="document.getElementById('memberModal').classList.add('hidden')" class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50">Cancel</button>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-bold">Save Member</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const allMembers = @json($allMembers ?? []); 
        function filterMembers(groupId) {
            const memberSelect = document.getElementById('memberSelect');
            memberSelect.innerHTML = '<option value="">-- Choose Member --</option>';
            allMembers.filter(m => m.group_id == groupId).forEach(m => {
                memberSelect.innerHTML += `<option value="${m.id}">${m.name}</option>`;
            });
        }

        function filterHistory() {
            const input = document.getElementById("historySearch");
            const filter = input.value.toUpperCase();
            const table = document.getElementById("historyTable");
            const tr = table.getElementsByTagName("tr");
            for (let i = 1; i < tr.length; i++) {
                const td = tr[i].getElementsByClassName("name-cell")[0];
                if (td) {
                    const textValue = td.textContent || td.innerText;
                    tr[i].style.display = textValue.trim().toUpperCase().indexOf(filter) > -1 ? "" : "none";
                }
            }
        }
    </script>
</body>
</html>