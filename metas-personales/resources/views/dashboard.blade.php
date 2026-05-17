<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
            <a href="{{ route('goals.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 rounded-md text-xs text-white font-semibold uppercase hover:bg-indigo-700">
                Nueva meta
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <x-flash />

            @if (auth()->user()->motivation_msg)
                <div class="bg-indigo-50 border border-indigo-100 rounded-lg p-4 text-indigo-900 italic">
                    "{{ auth()->user()->motivation_msg }}"
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-white p-6 rounded-lg shadow-sm">
                    <p class="text-sm text-gray-500">Total metas</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $stats['total'] }}</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-sm">
                    <p class="text-sm text-gray-500">Activas</p>
                    <p class="text-3xl font-bold text-green-600">{{ $stats['active'] }}</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-sm">
                    <p class="text-sm text-gray-500">Completadas</p>
                    <p class="text-3xl font-bold text-blue-600">{{ $stats['done'] }}</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-sm">
                    <p class="text-sm text-gray-500">Progreso promedio</p>
                    <p class="text-3xl font-bold text-indigo-600">{{ $stats['avg_progress'] }}%</p>
                </div>
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">Metas recientes</h3>
                    <a href="{{ route('goals.index') }}" class="text-sm text-indigo-600 hover:underline">Ver todas →</a>
                </div>

                @if ($recentGoals->isEmpty())
                    <p class="text-gray-500">Aún no tienes metas. <a href="{{ route('goals.create') }}" class="text-indigo-600 underline">Crea tu primera meta</a>.</p>
                @else
                    <ul class="divide-y divide-gray-100">
                        @foreach ($recentGoals as $goal)
                            <li class="py-3 flex justify-between items-center gap-4">
                                <div>
                                    <a href="{{ route('goals.show', $goal) }}" class="font-medium text-gray-800 hover:text-indigo-600">{{ $goal->title }}</a>
                                    <p class="text-xs text-gray-500">
                                        {{ ucfirst($goal->category) }} · {{ $goal->status }}
                                        @if (auth()->user()->isAdmin() && $goal->relationLoaded('user'))
                                            · {{ $goal->user->name }}
                                        @endif
                                    </p>
                                </div>
                                <div class="text-right shrink-0">
                                    <span class="text-sm font-semibold text-indigo-600">{{ $goal->progress }}%</span>
                                    <p class="text-xs text-gray-400">{{ $goal->milestones_count }} hitos · {{ $goal->progress_logs_count }} logs</p>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
