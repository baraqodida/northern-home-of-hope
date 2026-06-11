<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Northern Home of Hope Portal</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-gray-100 font-sans antialiased flex items-center justify-center min-h-screen p-4">

    <div class="max-w-md w-full bg-white rounded-2xl border border-gray-200 shadow-xl overflow-hidden">
        <div class="px-6 py-5 bg-[#1A365D] text-white text-center">
            <h2 class="text-xl font-bold tracking-wide">Northern Home of Hope</h2>
            <p class="text-xs text-blue-200 mt-1 uppercase tracking-wider font-medium">Administrative Access Portal</p>
        </div>

        <form action="{{ route('login.authenticate') }}" method="POST" class="p-6 space-y-4">
            @csrf

            {{-- Error Flash Alert Block --}}
            @if($errors->any())
                <div class="p-3.5 bg-red-50 border border-red-200 text-red-800 rounded-xl text-xs font-medium space-y-1">
                    @foreach ($errors->all() as $error)
                        <p>⚠️ {{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <div>
                <label for="email" class="block text-xs font-semibold uppercase tracking-wider text-gray-600 mb-1.5">Account Email Address</label>
                <input type="email" name="email" id="email" required value="{{ old('email') }}" placeholder="admin@homeofhope.org" class="w-full bg-white border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label for="password" class="block text-xs font-semibold uppercase tracking-wider text-gray-600 mb-1.5">Secure Password</label>
                <input type="password" name="password" id="password" required placeholder="••••••••" class="w-full bg-white border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="pt-2">
                <button type="submit" class="w-full bg-[#2B6CB0] hover:bg-blue-700 text-white text-sm font-bold py-2.5 rounded-lg transition-colors shadow-sm cursor-pointer tracking-wide">
                    🔐 Verify Identity & Enter
                </button>
            </div>
        </form>
    </div>

</body>
</html>