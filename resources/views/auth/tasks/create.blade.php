<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Assign New Task</h2>
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

                <form action="{{ route('tasks.store') }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label class="block font-semibold">Title</label>
                        <input type="text" name="title" required
                               class="w-full border-gray-300 rounded mt-1" />
                    </div>

                    <div class="mb-4">
                        <label class="block font-semibold">Description</label>
                        <textarea name="description" rows="4"
                                  class="w-full border-gray-300 rounded mt-1"></textarea>
                    </div>

                    <div class="mb-4">
                        <label class="block font-semibold">Assign To</label>
                        <select name="assigned_to" required class="w-full border-gray-300 rounded mt-1">
                            <option value="">-- Select User --</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block font-semibold">Deadline</label>
                        <input type="date" name="deadline" class="w-full border-gray-300 rounded mt-1" />
                    </div>

                    <button type="submit"
                            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
                        Assign Task
                    </button>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
