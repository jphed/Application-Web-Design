<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\TicketAttachment;
use App\Services\HuggingFaceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TicketWebController extends Controller
{
    // GET /tickets
    public function index()
    {
        $tickets = Ticket::orderBy('fecha_reporte', 'desc')->get();

        return view('tickets.index', compact('tickets'));
    }

    // GET /tickets/create
    public function create()
    {
        return view('tickets.create');
    }

    // POST /tickets
    public function store(Request $request)
    {
        $request->validate([
            'attachments.*' => 'nullable|file|max:10240', // 10MB máx
        ]);

        $ticket = Ticket::create($request->except('attachments'));

        if ($request->hasFile('attachments')) {
            $imagePaths = [];
            
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('ticket-attachments', 'public');
                $ticket->attachments()->create([
                    'original_name' => $file->getClientOriginalName(),
                    'file_path' => $path,
                    'mime_type' => $file->getMimeType(),
                    'size' => $file->getSize(),
                    'type' => str_starts_with($file->getMimeType(), 'image/') ? 'image' : 'document',
                ]);
                
                // Si es una imagen, agregarla para análisis de IA
                if (str_starts_with($file->getMimeType(), 'image/')) {
                    $imagePaths[] = $path;
                }
            }
            
            // Realizar análisis de IA si hay imágenes
            if (!empty($imagePaths)) {
                $this->performAIAnalysis($ticket, $imagePaths);
            }
        }

        return redirect()->route('admin.tickets.show', $ticket)
            ->with('success', 'Ticket creado con adjuntos' . (!empty($imagePaths) ? ' y análisis de IA' : ''));
    }

    // GET /tickets/{ticket}
    public function show(Ticket $ticket)
    {
        $ticket->load('attachments');
        return view('tickets.show', compact('ticket'));
    }

    // GET /tickets/{ticket}/edit
    public function edit(Ticket $ticket)
    {
        return view('tickets.edit', compact('ticket'));
    }

    // PUT /tickets/{ticket}
    public function update(Request $request, Ticket $ticket)
    {
        $request->validate([
            'attachments.*' => 'nullable|file|max:10240', // 10MB máx
        ]);

        $ticket->update($request->except('attachments'));

        if ($request->hasFile('attachments')) {
            // Eliminar adjuntos anteriores y sus archivos físicos
            $this->deleteExistingAttachments($ticket);
            
            $imagePaths = [];
            
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('ticket-attachments', 'public');
                $ticket->attachments()->create([
                    'original_name' => $file->getClientOriginalName(),
                    'file_path' => $path,
                    'mime_type' => $file->getMimeType(),
                    'size' => $file->getSize(),
                    'type' => str_starts_with($file->getMimeType(), 'image/') ? 'image' : 'document',
                ]);
                
                // Si es una imagen, agregarla para análisis de IA
                if (str_starts_with($file->getMimeType(), 'image/')) {
                    $imagePaths[] = $path;
                }
            }
            
            // Realizar análisis de IA si hay imágenes nuevas
            if (!empty($imagePaths)) {
                $this->performAIAnalysis($ticket, $imagePaths, true);
            }
        }

        return redirect()->route('admin.tickets.show', $ticket)
            ->with('success', 'Ticket actualizado. Adjuntos anteriores reemplazados' . (!empty($imagePaths) ? ' y análisis de IA actualizado' : ''));
    }

    // PATCH /tickets/{ticket}/close
    public function close(Ticket $ticket)
    {
        if(!in_array($ticket->status, ['pendiente', 'en curso'])){
            return redirect()->back()
                ->with('error', 'No se puede cerrar este ticket');
        }

        $ticket->status = 'finalizada';
        $ticket->fecha_resolucion = now();
        $ticket->save();

        return $this->redirectToIndex()
            ->with('success', 'Ticket cerrado correctamente');
    }

    // DELETE /tickets/{ticket}
    public function destroy(Ticket $ticket)
    {
        $ticket->delete();

        return $this->redirectToIndex()
            ->with('success', 'Ticket eliminado.');
    }

    private function redirectToIndex()
    {
        $routePrefix = request()->route()->getPrefix();

        if (str_starts_with($routePrefix, 'admin')) {
            return redirect()->route('admin.tickets.index');
        }

        return redirect()->route('usuario.tickets.index');
    }

    /**
     * Realiza análisis de IA en las imágenes adjuntas
     */
    private function performAIAnalysis(Ticket $ticket, array $imagePaths, bool $isUpdate = false)
    {
        try {
            $huggingFaceService = new HuggingFaceService();
            
            if (count($imagePaths) === 1) {
                // Análisis individual
                $result = $huggingFaceService->analyzeImage($imagePaths[0]);
                
                if ($result['success']) {
                    $ticket->ai_analysis = $result['analysis'];
                    
                    // Actualizar categoría y urgencia si se sugieren (siempre en actualizaciones)
                    if (isset($result['analysis']['categoria_sugerida'])) {
                        $ticket->categoria = $result['analysis']['categoria_sugerida'];
                    }
                    
                    $ticket->save();
                }
            } else {
                // Análisis múltiple
                $result = $huggingFaceService->analyzeMultipleImages($imagePaths);
                
                if ($result['success']) {
                    $ticket->ai_analysis = $result['combined_analysis'];
                    
                    // Actualizar categoría y urgencia si se sugieren (siempre en actualizaciones)
                    if (isset($result['combined_analysis']['categoria_sugerida'])) {
                        $ticket->categoria = $result['combined_analysis']['categoria_sugerida'];
                    }
                    
                    $ticket->save();
                }
            }
        } catch (\Exception $e) {
            // Log del error pero no interrumpir el flujo
            \Log::error('Error en análisis de IA: ' . $e->getMessage());
        }
    }

    /**
     * Elimina todos los adjuntos existentes de un ticket y sus archivos físicos
     */
    private function deleteExistingAttachments(Ticket $ticket)
    {
        try {
            $attachments = $ticket->attachments;
            
            foreach ($attachments as $attachment) {
                // Eliminar archivo físico del storage
                if (Storage::disk('public')->exists($attachment->file_path)) {
                    Storage::disk('public')->delete($attachment->file_path);
                }
                
                // Eliminar registro de la base de datos
                $attachment->delete();
            }
            
            // Limpiar análisis de IA anterior para generar uno nuevo
            $ticket->ai_analysis = null;
            $ticket->save();
            
        } catch (\Exception $e) {
            // Log del error pero no interrumpir el flujo
            \Log::error('Error eliminando adjuntos anteriores: ' . $e->getMessage());
        }
    }
}
