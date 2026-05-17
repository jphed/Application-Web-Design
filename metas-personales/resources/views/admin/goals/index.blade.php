<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Panel admin — Todas las metas</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <x-flash />

            <div class="bg-amber-50 border border-amber-200 rounded-lg p-4 text-sm text-amber-900">
                Vista de administrador: puedes ver las metas de todos los usuarios.
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                    <div>
                        <x-input-label for="user_id" value="Usuario" />
                        <select name="user_id" id="user_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            <option value="">Todos</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}" @selected(request('user_id') == $user->id)>{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <x-input-label for="status" value="Estado" />
                        <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            <option value="">Todos</option>
                            @foreach (['active', 'paused', 'done'] as $st)
                                <option value="{{ $st }}" @selected(request('status') === $st)>{{ $st }}</option>
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
                    <div class="flex items-end">
                        <x-primary-button>Filtrar</x-primary-button>
                    </div>
                </form>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left">Título</th>
                                <th class="px-4 py-2 text-left">Usuario</th>
                                <th class="px-4 py-2 text-left">Categoría</th>
                                <th class="px-4 py-2 text-left">Estado</th>
                                <th class="px-4 py-2 text-left">Progreso</th>
                                <th class="px-4 py-2"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach ($goals as $goal)
                                <tr>
                                    <td class="px-4 py-3">{{ $goal->title }}</td>
                                    <td class="px-4 py-3">{{ $goal->user->name }}</td>
                                    <td class="px-4 py-3">{{ ucfirst($goal->category) }}</td>
                                    <td class="px-4 py-3">{{ $goal->status }}</td>
                                    <td class="px-4 py-3">{{ $goal->progress }}%</td>
                                    <td class="px-4 py-3 text-right">
                                        <a href="{{ route('goals.show', $goal) }}" class="text-indigo-600 hover:underline">Ver detalle</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">{{ $goals->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>
