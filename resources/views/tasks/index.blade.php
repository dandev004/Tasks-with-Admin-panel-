<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Tasks') }}
            </h2>

            <a href="{{ route('tasks.create') }}"
               class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                {{ __('Create Task') }}
            </a>
        </div>
    </x-slot>

    @if ($tasks->count() > 0)
        @foreach ($tasks as $task)
            <div class="p-4">
                <a href="{{ route('tasks.show', $task) }}" class="font-bold text-lg text-gray-700">
                    {{ $task['title'] }}
                </a>
                <p class="text-gray-600">{{ $task['description'] }}</p>
            </div>
        @endforeach
    @else
        <p>{{ __('No tasks found') }}</p>
    @endif
</x-app-layout>