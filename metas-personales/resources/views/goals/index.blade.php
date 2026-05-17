<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Mis metas</h2>
            <a href="{{ route('goals.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                Nueva meta
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <x-flash />

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <div>
                        <x-input-label for="status" value="Estado" />
                        <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            <option value="">Todos</option>
                            @foreach (['active' => 'Activa', 'paused' => 'Pausada', 'done' => 'Completada'] as $val => $lab)
                                <option value="{{ $val }}" @selected(request('status') === $val)>{{ $lab }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <x-input-label for="category" value="Categoría" />
                        <select name="category" id="category" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            <option value="">Todas</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat }}" @selected(request('category') === $cat)>{{ ucfirst($cat) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex items-end gap-2">
                        <x-primary-button>Filtrar</x-primary-button>
                        <a href="{{ route('goals.index') }}" class="text-sm text-gray-600 hover:text-gray-900 underline">Limpiar</a>
                    </div>
                </form>

                @if ($goals->isEmpty())
                    <p class="text-gray-500">No hay metas. <a href="{{ route('goals.create') }}" class="text-indigo-600 underline">Crea la primera</a>.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-left">Título</th>
                                    @if (auth()->user()->isAdmin())
                                        <th class="px-4 py-2 text-left">Usuario</th>
                                    @endif
                                    <th class="px-4 py-2 text-left">Categoría</th>
                                    <th class="px-4 py-2 text-left">Estado</th>
                                    <th class="px-4 py-2 text-left">Progreso</th>
                                    <th class="px-4 py-2"></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach ($goals as $goal)
                                    <tr>
                                        <td class="px-4 py-3 font-medium">{{ $goal->title }}</td>
                                        @if (auth()->user()->isAdmin())
                                            <td class="px-4 py-3">{{ $goal->user->name ?? '-' }}</td>
                                        @endif
                                        <td class="px-4 py-3">{{ ucfirst($goal->category) }}</td>
                                        <td class="px-4 py-3">
                                            <span class="px-2 py-1 rounded text-xs {{ $goal->status === 'active' ? 'bg-green-100 text-green-800' : ($goal->status === 'done' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800') }}">
                                                {{ $goal->status }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="w-24 bg-gray-200 rounded-full h-2">
                                                <div class="bg-indigo-600 h-2 rounded-full" style="width: {{ $goal->progress }}%"></div>
                                            </div>
                                            <span class="text-xs text-gray-500">{{ $goal->progress }}%</span>
                                        </td>
                                        <td class="px-4 py-3 text-right">
                                            <a href="{{ route('goals.show', $goal) }}" class="text-indigo-600 hover:underline">Ver</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">{{ $goals->links() }}</div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
