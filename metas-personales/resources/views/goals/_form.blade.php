@php
    $goal = $goal ?? null;
@endphp

<div>
    <x-input-label for="title" value="Título" />
    <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" :value="old('title', $goal?->title)" required />
    <x-input-error class="mt-2" :messages="$errors->get('title')" />
</div>

<div class="mt-4">
    <x-input-label for="description" value="Descripción" />
    <textarea id="description" name="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description', $goal?->description) }}</textarea>
    <x-input-error class="mt-2" :messages="$errors->get('description')" />
</div>

<div class="mt-4">
    <x-input-label for="category" value="Categoría" />
    <select id="category" name="category" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
        <option value="">Selecciona...</option>
        @foreach ($categories as $cat)
            <option value="{{ $cat }}" @selected(old('category', $goal?->category) === $cat)>{{ ucfirst($cat) }}</option>
        @endforeach
    </select>
    <x-input-error class="mt-2" :messages="$errors->get('category')" />
</div>

<div class="mt-4">
    <x-input-label for="deadline" value="Fecha límite" />
    <x-text-input id="deadline" name="deadline" type="date" class="mt-1 block w-full" :value="old('deadline', $goal?->deadline?->format('Y-m-d'))" />
    <x-input-error class="mt-2" :messages="$errors->get('deadline')" />
</div>

<div class="mt-4">
    <x-input-label for="status" value="Estado" />
    <select id="status" name="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
        @foreach (['active' => 'Activa', 'paused' => 'Pausada', 'done' => 'Completada'] as $value => $label)
            <option value="{{ $value }}" @selected(old('status', $goal?->status ?? 'active') === $value)>{{ $label }}</option>
        @endforeach
    </select>
    <x-input-error class="mt-2" :messages="$errors->get('status')" />
</div>

@if ($goal)
    <div class="mt-4">
        <x-input-label for="progress" value="Progreso (%)" />
        <x-text-input id="progress" name="progress" type="number" min="0" max="100" class="mt-1 block w-full" :value="old('progress', $goal->progress)" required />
        <x-input-error class="mt-2" :messages="$errors->get('progress')" />
    </div>
@endif
