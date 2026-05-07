<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Sección para crear nuevo ticket -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Crear Nuevo Ticket</h3>
                        <a href="{{ route('tickets.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus mr-2"></i>Crear Ticket Completo
                        </a>
                    </div>
                    
                    <!-- Formulario rápido para crear ticket -->
                    <form action="{{ route('tickets.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Número de Reporte *</label>
                                <input type="text" name="numero_reporte" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    placeholder="TKT-2024-0001">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Nombre del Cliente *</label>
                                <input type="text" name="cliente_nombre" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Email del Cliente</label>
                                <input type="email" name="cliente_email"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Departamento *</label>
                                <input type="text" name="departamento" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Categoría *</label>
                                <select name="categoria" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="">-- Selecciona --</option>
                                    <option value="software">Software</option>
                                    <option value="hardware">Hardware</option>
                                    <option value="comunicaciones">Comunicaciones</option>
                                    <option value="plataformas">Plataformas</option>
                                    <option value="email">Email</option>
                                    <option value="otro">Otro</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Nivel de Urgencia *</label>
                                <select name="nivel_urgencia" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="baja">Baja</option>
                                    <option value="media">Media</option>
                                    <option value="alta">Alta</option>
                                    <option value="critica">Crítica</option>
                                </select>
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Descripción Corta *</label>
                            <input type="text" name="descripcion_corta" required maxlength="255"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Descripción Detallada</label>
                            <textarea name="descripcion_detallada" rows="3"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Adjuntar imágenes y documentos</label>
                            <input type="file" name="attachments[]" multiple
                                accept="image/*,.pdf,.doc,.docx,.txt,.xls,.xlsx"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <p class="mt-1 text-sm text-gray-500">Máximo 10 MB por archivo. Puedes seleccionar varios. La IA analizará las imágenes automáticamente.</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Fecha de Reporte *</label>
                            <input type="datetime-local" name="fecha_reporte" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                        
                        <div class="flex justify-end space-x-3">
                            <a href="{{ route('tickets.index') }}" class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                                Ver Todos los Tickets
                            </a>
                            <button type="submit" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                                Crear Ticket
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Sección de tickets recientes -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Tickets Recientes</h3>
                    
                    @if($tickets = \App\Models\Ticket::orderBy('fecha_reporte', 'desc')->take(5)->get())
                        @if($tickets->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ticket</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cliente</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Categoría</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Urgencia</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($tickets as $ticket)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                    {{ $ticket->numero_reporte }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ $ticket->cliente_nombre }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ ucfirst($ticket->categoria) }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                        @if($ticket->nivel_urgencia == 'critica') bg-red-100 text-red-800
                                                        @elseif($ticket->nivel_urgencia == 'alta') bg-orange-100 text-orange-800
                                                        @elseif($ticket->nivel_urgencia == 'media') bg-yellow-100 text-yellow-800
                                                        @else bg-green-100 text-green-800 @endif">
                                                        {{ ucfirst($ticket->nivel_urgencia) }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                        @if($ticket->status == 'finalizada') bg-green-100 text-green-800
                                                        @elseif($ticket->status == 'en_curso') bg-blue-100 text-blue-800
                                                        @elseif($ticket->status == 'en_espera') bg-yellow-100 text-yellow-800
                                                        @elseif($ticket->status == 'cancelada') bg-red-100 text-red-800
                                                        @else bg-gray-100 text-gray-800 @endif">
                                                        {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                    <a href="{{ route('tickets.show', $ticket) }}" class="text-indigo-600 hover:text-indigo-900">Ver</a>
                                                    <a href="{{ route('tickets.edit', $ticket) }}" class="ml-2 text-indigo-600 hover:text-indigo-900">Editar</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-gray-500">No hay tickets registrados aún.</p>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
