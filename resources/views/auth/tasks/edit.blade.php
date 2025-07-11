<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Edit Task</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto">
            <div class="bg-white p-6 shadow-sm rounded">

                @if ($errors->any())
                    <div class="mb-4 text-red-600">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>- {{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('tasks.update', $task->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    @if(auth()->user()->role === 'admin')
                        <div class="mb-4">
                            <label class="block font-semibold">Title</label>
                            <input type="text" name="title" value="{{ $task->title }}" required
                                   class="w-full border-gray-300 rounded mt-1" />
                        </div>

                        <div class="mb-4">
                            <label class="block font-semibold">Description</label>
                            <textarea name="description" rows="4"
                                      class="w-full border-gray-300 rounded mt-1">{{ $task->description }}</textarea>
                        </div>

                        <div class="mb-4">
                            <label class="block font-semibold">Assign To</label>
                            <select name="assigned_to" class="w-full border-gray-300 rounded mt-1">
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ $task->assigned_to == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }} ({{ $user->email }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="block font-semibold">Deadline</label>
                            <input type="date" name="deadline" value="{{ $task->deadline }}"
                                   class="w-full border-gray-300 rounded mt-1" />
                        </div>
                    @endif

                    <div class="mb-4">
                        <label class="block font-semibold">Status</label>
                        <select name="status" class="w-full border-gray-300 rounded mt-1">
                            <option value="Pending" {{ $task->status == 'Pending' ? 'selected' : '' }}>Pending</option>
                            <option value="In Progress" {{ $task->status == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="Completed" {{ $task->status == 'Completed' ? 'selected' : '' }}>Completed</option>
                        </select>
                    </div>

                    <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                        Update Task
                    </button>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
