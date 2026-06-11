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
        
        {{-- Flash Session Success Banner Alert --}}
        @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-800 rounded-xl text-sm font-medium flex items-center justify-between shadow-sm">
                <div class="flex items-center gap-2">
                    🚀 <span>{{ session('success') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-green-600 hover:text-green-800 font-bold text-lg cursor-pointer">&times;</button>
            </div>
        @endif
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Total Funds Collected</p>
                    <h3 class="text-3xl font-bold text-gray-900 mt-1">Ksh {{ number_format($totalCollected, 2) }}</h3>
                </div>
                <div class="p-3 bg-green-50 text-green-600 rounded-lg">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Active Registered Base</p>
                    <h3 class="text-3xl font-bold text-gray-900 mt-1">{{ $totalMembers }} Members</h3>
                </div>
                <div class="p-3 bg-blue-50 text-[#2B6CB0] rounded-lg">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                <h2 class="text-lg font-semibold text-gray-900">Member Structural Ledger</h2>
                <div class="flex flex-wrap items-center gap-2">
                    <span class="text-xs bg-gray-200 text-gray-700 font-semibold px-2.5 py-1 rounded">Live Records</span>
                    <a href="{{ route('export.pdf', request()->query()) }}" class="bg-red-600 hover:bg-red-700 text-white text-xs font-bold px-3 py-2 rounded-lg shadow-sm transition-colors cursor-pointer flex items-center gap-1">📄 Export PDF</a>
                    <a href="{{ route('export.excel', request()->query()) }}" class="bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold px-3 py-2 rounded-lg shadow-sm transition-colors cursor-pointer flex items-center gap-1">📊 Export Excel</a>
                    <button onclick="document.getElementById('contributionModal').classList.remove('hidden')" class="bg-green-600 hover:bg-green-700 text-white text-xs font-bold px-3.5 py-2 rounded-lg shadow-sm transition-colors cursor-pointer flex items-center gap-1.5">➕ Record Contribution</button>
                </div>
            </div>

            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                <form action="{{ url('/dashboard') }}" method="GET" class="flex flex-wrap items-end gap-4">
                    <div class="flex-1 min-w-[200px]">
                        <label for="group_id" class="block text-xs font-semibold uppercase tracking-wider text-gray-600 mb-1.5">Filter By Group Cluster</label>
                        <select name="group_id" id="group_id" class="w-full bg-white border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">-- All 15 Groups --</option>
                            @foreach($allGroups as $g)
                                <option value="{{ $g->id }}" {{ request('group_id') == $g->id ? 'selected' : '' }}>{{ $g->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex-1 min-w-[200px]">
                        <label for="sub_county" class="block text-xs font-semibold uppercase tracking-wider text-gray-600 mb-1.5">Filter By Sub-County</label>
                        <select name="sub_county" id="sub_county" class="w-full bg-white border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">-- All Sub-Counties --</option>
                            @foreach($allSubCounties as $sub)
                                <option value="{{ $sub }}" {{ request('sub_county') == $sub ? 'selected' : '' }}>{{ $sub }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex gap-2">
                        <button type="submit" class="bg-[#2B6CB0] hover:bg-blue-700 text-white text-sm font-medium px-5 py-2 rounded-lg transition-colors shadow-sm cursor-pointer">Apply Filters</button>
                        @if(request()->filled('group_id') || request()->filled('sub_county'))
                            <a href="{{ url('/dashboard') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 text-sm font-medium px-4 py-2 rounded-lg transition-colors text-center flex items-center justify-center">Clear</a>
                        @endif
                    </div>
                </form>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-100 text-xs font-semibold uppercase tracking-wider text-gray-600 border-b border-gray-200">
                            <th class="px-6 py-3.5">Member Identity</th>
                            <th class="px-6 py-3.5">Group Assignment</th>
                            <th class="px-6 py-3.5">Contact Detail</th>
                            <th class="px-6 py-3.5">Regional Demographics (County/Sub/Ward)</th>
                            <th class="px-6 py-3.5">Allocation Purpose</th>
                            <th class="px-6 py-3.5">Status</th>
                            <th class="px-6 py-3.5 text-right">Amount Contributed</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 text-sm text-gray-700">
                        @php $currentGroup = null; @endphp
                        @foreach($contributions as $item)
                            @php $memberGroupName = $item->user->group ? $item->user->group->name : 'Unassigned'; @endphp
                            @if($currentGroup !== $memberGroupName)
                                @php $currentGroup = $memberGroupName; @endphp
                                <tr class="bg-slate-200 border-y border-slate-300">
                                    <td colspan="7" class="px-6 py-3 text-xs font-bold uppercase tracking-wider text-slate-800 bg-slate-200 shadow-inner">🚀 {{ $currentGroup == 'Unassigned' ? 'UNASSIGNED MEMBERS' : $currentGroup . ' MEMBERS' }}</td>
                                </tr>
                            @endif
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 font-medium text-gray-900">{{ $item->user->name }}</td>
                                <td class="px-6 py-4"><span class="bg-blue-50 text-blue-700 text-xs px-2.5 py-1 rounded font-semibold border border-blue-100">{{ $memberGroupName }}</span></td>
                                <td class="px-6 py-4 text-gray-500 font-mono">{{ $item->user->phone_number ?? 'N/A' }}</td>
                                <td class="px-6 py-4 text-gray-600 text-xs"><span class="font-medium text-gray-900">{{ $item->user->county }}</span> ➔ {{ $item->user->sub_county }} ➔ <span class="text-gray-500 italic">{{ $item->user->ward }}</span></td>
                                <td class="px-6 py-4"><span class="bg-slate-100 text-slate-800 text-xs px-2.5 py-1 rounded font-medium border border-slate-200">{{ $item->purpose }}</span></td>
                                <td class="px-6 py-4">@if($item->status == 'completed') <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Completed</span> @else <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Pending</span> @endif</td>
                                <td class="px-6 py-4 text-right font-semibold text-gray-900">Ksh {{ number_format($item->amount, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">{{ $contributions->links() }}</div>
        </div>
    </main>

    {{-- MODAL WITH DYNAMIC GROUP FILTER --}}
    <div id="contributionModal" class="hidden fixed inset-0 bg-slate-900/50 backdrop-blur-xs flex items-center justify-center p-4 z-50">
        <div class="bg-white rounded-2xl border border-gray-200 shadow-xl max-w-md w-full overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200 flex justify-between items-center">
                <h3 class="font-bold text-gray-900">New Contribution Entry</h3>
                <button onclick="document.getElementById('contributionModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600 text-xl font-bold cursor-pointer">&times;</button>
            </div>
            
            <form action="{{ route('contributions.store') }}" method="POST" class="p-6 space-y-4">
                @csrf
                
                {{-- Group Filter for Member Dropdown --}}
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-gray-600 mb-1">Filter Members by Group</label>
                    <select id="groupFilter" onchange="filterMembers()" class="w-full bg-white border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 mb-2">
                        <option value="">-- All Groups --</option>
                        @foreach($allGroups as $group)
                            <option value="{{ $group->id }}">{{ $group->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="user_id" class="block text-xs font-semibold uppercase tracking-wider text-gray-600 mb-1">Select Member</label>
                    <select name="user_id" id="user_id" required class="w-full bg-white border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">-- Choose Member --</option>
                        @foreach($allUsers as $user)
                            <option value="{{ $user->id }}" data-group="{{ $user->group_id }}">
                                {{ $user->name }} ({{ $user->group->name ?? 'No Group Cluster' }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="weeks_paid" class="block text-xs font-semibold uppercase tracking-wider text-gray-600 mb-1">Weeks Being Paid</label>
                    <input type="number" name="weeks_paid" id="weeks_paid" min="1" required placeholder="e.g. 1" class="w-full bg-white border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <p class="text-[10px] text-gray-400 mt-1 uppercase font-bold">System auto-calculates Ksh 100 per week</p>
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-gray-600 mb-1">Payment Lifecycle Status</label>
                    <div class="flex gap-4 mt-1">
                        <label class="flex items-center gap-2 text-sm font-medium text-gray-700 cursor-pointer">
                            <input type="radio" name="status" value="completed" checked class="text-blue-600 focus:ring-2 focus:ring-blue-500"> Completed
                        </label>
                        <label class="flex items-center gap-2 text-sm font-medium text-gray-700 cursor-pointer">
                            <input type="radio" name="status" value="pending" class="text-blue-600 focus:ring-2 focus:ring-blue-500"> Pending
                        </label>
                    </div>
                </div>

                <div class="flex justify-end gap-2 pt-4 border-t border-gray-100">
                    <button type="button" onclick="document.getElementById('contributionModal').classList.add('hidden')" class="bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium px-4 py-2 rounded-lg transition-colors cursor-pointer">Cancel</button>
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white text-sm font-medium px-5 py-2 rounded-lg transition-colors shadow-sm cursor-pointer">Save Record</button>
                </div>
            </form>
        </div>
    </div>

    <script>
    function filterMembers() {
        const groupFilter = document.getElementById('groupFilter').value;
        const userSelect = document.getElementById('user_id');
        const options = userSelect.querySelectorAll('option');

        options.forEach(option => {
            if (option.value === "") return; // Keep "Choose Member" placeholder visible
            const groupMatch = groupFilter === "" || option.getAttribute('data-group') === groupFilter;
            option.style.display = groupMatch ? 'block' : 'none';
        });

        // If selected member is no longer visible, reset dropdown
        if (userSelect.options[userSelect.selectedIndex].style.display === 'none') {
            userSelect.selectedIndex = 0;
        }
    }
    </script>
</body>
</html>