<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-2">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $goal->title }}</h2>
            <div class="flex gap-2">
                <a href="{{ route('goals.edit', $goal) }}" class="text-sm text-indigo-600 hover:underline">Editar</a>
                <form method="POST" action="{{ route('goals.destroy', $goal) }}" onsubmit="return confirm('¿Eliminar esta meta?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-sm text-red-600 hover:underline">Eliminar</button>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <x-flash />

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <div class="grid md:grid-cols-2 gap-4 text-sm">
                    <p><span class="font-medium text-gray-700">Categoría:</span> {{ ucfirst($goal->category) }}</p>
                    <p><span class="font-medium text-gray-700">Estado:</span> {{ $goal->status }}</p>
                    <p><span class="font-medium text-gray-700">Progreso:</span> {{ $goal->progress }}%</p>
                    <p><span class="font-medium text-gray-700">Fecha límite:</span> {{ $goal->deadline?->format('d/m/Y') ?? 'Sin fecha' }}</p>
                </div>
                @if ($goal->description)
                    <p class="mt-4 text-gray-600">{{ $goal->description }}</p>
                @endif
                <div class="mt-4 w-full bg-gray-200 rounded-full h-3">
                    <div class="bg-indigo-600 h-3 rounded-full" style="width: {{ $goal->progress }}%"></div>
                </div>
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Hitos</h3>

                <form method="POST" action="{{ route('goals.milestones.store', $goal) }}" class="mb-6 grid md:grid-cols-4 gap-3 items-end border-b pb-6">
                    @csrf
                    <div class="md:col-span-2">
                        <x-input-label for="m_title" value="Nuevo hito" />
                        <x-text-input id="m_title" name="title" class="mt-1 block w-full" required />
                        <x-input-error :messages="$errors->get('title')" class="mt-1" />
                    </div>
                    <div>
                        <x-input-label for="m_due" value="Fecha" />
                        <x-text-input id="m_due" name="due_date" type="date" class="mt-1 block w-full" />
                    </div>
                    <div>
                        <x-primary-button type="submit">Agregar</x-primary-button>
                    </div>
                </form>

                <ul class="space-y-3">
                    @forelse ($goal->milestones as $milestone)
                        <li class="flex items-start gap-3 p-3 border rounded-lg {{ $milestone->completed ? 'bg-gray-50 opacity-75' : '' }}">
                            <form method="POST" action="{{ route('goals.milestones.toggle', [$goal, $milestone]) }}">
                                @csrf
                                @method('PATCH')
                                <input type="checkbox" onchange="this.form.submit()" @checked($milestone->completed) class="rounded border-gray-300 text-indigo-600 mt-1">
                            </form>
                            <div class="flex-1">
                                <p class="font-medium {{ $milestone->completed ? 'line-through text-gray-500' : '' }}">{{ $milestone->title }}</p>
                                @if ($milestone->due_date)
                                    <p class="text-xs text-gray-500">Vence: {{ $milestone->due_date->format('d/m/Y') }}</p>
                                @endif
                                @if ($milestone->notes)
                                    <p class="text-sm text-gray-600 mt-1">{{ $milestone->notes }}</p>
                                @endif
                            </div>
                            <form method="POST" action="{{ route('goals.milestones.destroy', [$goal, $milestone]) }}" onsubmit="return confirm('¿Eliminar hito?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-xs text-red-600 hover:underline">Eliminar</button>
                            </form>
                        </li>
                    @empty
                        <p class="text-gray-500 text-sm">Sin hitos aún.</p>
                    @endforelse
                </ul>
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Bitácora de progreso</h3>

                <form method="POST" action="{{ route('goals.progress-logs.store', $goal) }}" class="mb-6 space-y-3 border-b pb-6">
                    @csrf
                    <div>
                        <x-input-label for="note" value="Reflexión / avance" />
                        <textarea id="note" name="note" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>{{ old('note') }}</textarea>
                        <x-input-error :messages="$errors->get('note')" class="mt-1" />
                    </div>
                    <div class="grid md:grid-cols-2 gap-3">
                        <div>
                            <x-input-label for="progress_value" value="Progreso (%)" />
                            <x-text-input id="progress_value" name="progress_value" type="number" min="0" max="100" class="mt-1 block w-full" :value="old('progress_value', $goal->progress)" required />
                            <x-input-error :messages="$errors->get('progress_value')" class="mt-1" />
                        </div>
                        <div class="flex items-end">
                            <x-primary-button type="submit">Registrar entrada</x-primary-button>
                        </div>
                    </div>
                </form>

                <ul class="space-y-4">
                    @forelse ($goal->progressLogs as $log)
                        <li class="border-l-4 border-indigo-400 pl-4 py-1">
                            <p class="text-sm text-gray-500">{{ $log->logged_at->format('d/m/Y H:i') }} — {{ $log->progress_value }}%</p>
                            <p class="text-gray-800">{{ $log->note }}</p>
                            <form method="POST" action="{{ route('goals.progress-logs.destroy', [$goal, $log]) }}" class="mt-1" onsubmit="return confirm('¿Eliminar entrada?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-xs text-red-600 hover:underline">Eliminar</button>
                            </form>
                        </li>
                    @empty
                        <p class="text-gray-500 text-sm">Sin entradas en la bitácora.</p>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</x-app-layout>
