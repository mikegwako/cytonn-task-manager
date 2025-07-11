<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Tasks</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto">
            <div class="bg-white p-6 shadow-sm rounded">

                @if(session('success'))
                    <div class="bg-green-100 text-green-800 p-2 rounded mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                @if (auth()->user()->role === 'admin')
                    <a href="{{ route('tasks.create') }}"
                       class="bg-blue-600 text-white px-4 py-2 rounded mb-4 inline-block">Assign New Task</a>
                @endif

                <table class="w-full table-auto text-left mt-4">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="px-4 py-2">Title</th>
                            <th class="px-4 py-2">Assigned To</th>
                            <th class="px-4 py-2">Status</th>
                            <th class="px-4 py-2">Deadline</th>
                            <th class="px-4 py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($tasks as $task)
                            <tr class="border-t">
                                <td class="px-4 py-2">{{ $task->title }}</td>
                                <td class="px-4 py-2">{{ $task->user->name ?? '—' }}</td>
                                <td class="px-4 py-2">{{ $task->status }}</td>
                                <td class="px-4 py-2">{{ $task->deadline ?? '—' }}</td>
                                <td class="px-4 py-2 flex gap-2">
                                    <a href="{{ route('tasks.edit', $task) }}"
                                       class="bg-yellow-400 text-white px-3 py-1 rounded">Edit</a>
                                    @if(auth()->user()->role === 'admin')
                                        <form action="{{ route('tasks.destroy', $task) }}" method="POST"
                                              onsubmit="return confirm('Are you sure?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="bg-red-600 text-white px-3 py-1 rounded">Delete</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-4 text-center text-gray-500">No tasks found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</x-app-layout>
