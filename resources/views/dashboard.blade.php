<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-bold mb-4">Welcome, {{ auth()->user()->name }}</h3>

                @if (auth()->user()->role === 'admin')
                    <p class="text-green-600 mb-4">You are logged in as <strong>Admin</strong></p>

                    <a href="#" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded inline-block mb-2">
                        Manage Users
                    </a><br>

                    <a href="#" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded inline-block">
                        Manage Tasks
                    </a>
                @else
                    <p class="text-blue-600 mb-4">You are logged in as <strong>User</strong></p>

                    <a href="#" class="bg-purple-500 hover:bg-purple-600 text-white px-4 py-2 rounded inline-block">
                        View My Tasks
                    </a>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
