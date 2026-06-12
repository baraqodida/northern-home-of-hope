<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Import Members - Northern Home of Hope</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-gray-100 font-sans antialiased">

    <nav class="bg-[#1A365D] text-white px-6 py-4 shadow-md">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <h1 class="text-xl font-bold tracking-wide">Northern Home of Hope Portal</h1>
            <a href="{{ route('dashboard') }}" class="text-sm bg-[#2B6CB0] px-3 py-1 rounded-full font-medium hover:bg-blue-700 transition-colors">← Back to Dashboard</a>
        </div>
    </nav>

    <main class="max-w-2xl mx-auto px-4 py-12">
        
        {{-- Flash Session Success Banner --}}
        @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-800 rounded-xl text-sm font-medium flex items-center shadow-sm">
                🚀 {{ session('success') }}
            </div>
        @endif

        {{-- Error Banner --}}
        @if($errors->any())
            <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-800 rounded-xl text-sm font-medium shadow-sm">
                ❌ {{ $errors->first() }}
            </div>
        @endif

        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8">
            <h2 class="text-xl font-bold text-gray-900 mb-2">Bulk Member Import</h2>
            <p class="text-sm text-gray-600 mb-6">Use this tool to upload your member list. Ensure your CSV is saved in the exact column order below.</p>
            
            <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6 text-sm text-blue-800">
                <p class="font-bold">Required CSV Column Order:</p>
                <ol class="list-decimal ml-4 mt-2 space-y-1">
                    <li>Group Number (ID)</li>
                    <li>Name</li>
                    <li>County</li>
                    <li>Sub-county</li>
                    <li>Ward</li>
                    <li>Phone Number</li>
                </ol>
                <p class="mt-3 text-xs opacity-75 italic">Ensure there is no extra text or empty rows before the data.</p>
            </div>

            <form action="{{ route('members.import.submit') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-gray-600 mb-2">Upload CSV File</label>
                    <input type="file" name="csv_file" accept=".csv" required 
                           class="w-full text-sm text-gray-500 border border-gray-300 rounded-lg p-2 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-blue-50 file:text-blue-700 file:font-semibold hover:file:bg-blue-100 cursor-pointer">
                </div>
                
                <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 rounded-lg shadow-sm transition-colors cursor-pointer">
                    Process & Import Members
                </button>
            </form>
        </div>
    </main>

</body>
</html>