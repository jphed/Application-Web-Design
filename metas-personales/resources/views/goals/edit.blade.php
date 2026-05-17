<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Editar meta</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('goals.update', $goal) }}">
                    @csrf
                    @method('PUT')
                    @include('goals._form', ['goal' => $goal, 'categories' => $categories])
                    <div class="mt-6 flex gap-3">
                        <x-primary-button>Guardar cambios</x-primary-button>
                        <a href="{{ route('goals.show', $goal) }}" class="text-sm text-gray-600 hover:underline self-center">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
