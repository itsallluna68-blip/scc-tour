<!DOCTYPE html>
    <html lang="en">
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users Log</title>
    @vite('resources/css/app.css')
    </head>
    <body class="bg-gray-100 font-sans text-gray-800">

        {{-- Sidebar --}}
        @include('components.sidebar')

        <div class="flex-1 ml-60">
        {{-- Header --}}
        @include('components.header')

        {{-- Page Content --}}
        <main class="p-6">
            @yield('content')
        </main>
        </div>

    <main class="ml-56 mt-2 flex-1 p-6">

    <div class="flex justify-between items-center mb-4">
        <h2 class="text-2xl font-semibold text-indigo-900">Users Log</h2>
    </div>

    <div class="flex flex-wrap items-center gap-4 mb-5">
    <!-- Search -->
    <form class="flex items-center gap-2">
        <input 
        type="text" 
        name="search"
        placeholder="Work in Progress"
        class="w-64 border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
        >
        <button 
        type="submit"
        class="bg-indigo-900 text-white px-4 py-2 rounded-md hover:bg-indigo-800 transition"
        >
        Search
        </button>
    </form>

    <div class="flex items-center gap-2">
        <label for="month" class="font-medium text-gray-700">Sort:
        </label>
        <select id="month" class="border rounded px-2 py-1">
        <option value="all" selected>Default</option>
        <option value="all" selected>Date</option>
        <option value="all" selected>Time</option>
        <option value="all" selected>Action</option> 
        {{-- when action: have a dropdown of the possible actions taken (ex: logged in, user: deleted ID, place: deleted ID)--}}
        </select>
    </div>
    </div>
    
    {{-- table --}}
    <div class="mt-6 overflow-x-auto bg-white rounded-lg shadow-sm border border-gray-300">
    <table class="w-full text-sm">
        <thead class="bg-indigo-900 text-white">
        <tr>
            <th class="py-3 px-4 text-left w-12">ID</th>
            <th class="py-3 px-4 text-left">User Type</th>
            <th class="py-3 px-4 text-left">Username</th>
            <th class="py-3 px-4 text-left">Full Name</th>
            <th class="py-3 px-4 text-left">Date & Time</th>
            <th class="py-3 px-4 text-left">Action Taken</th>
        </tr>
        </thead>

        <tbody class="divide-y divide-gray-200">
        @foreach ($userLogs as $log)
            <td class="py-1 px-4">{{ $log->id }}</td>
            <td class="py-1 px-4">{{ $log->user_type }}</td>
            <td class="py-1 px-4">{{ $log->username }}</td>
            <td class="py-1 px-4">{{ $log->full_name }}</td>
            <td class="py-1 px-4">{{ \Carbon\Carbon::parse($log->date_time)->format('M d, Y h:i A') }}</td>
            <td class="py-1 px-4">{{ $log->action_taken }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    </div>



    </main>

    </body>
    </html>
