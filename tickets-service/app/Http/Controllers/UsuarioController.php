<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\TicketAttachment;
use App\Services\HuggingFaceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UsuarioController extends Controller
{
    public function dashboard()
    {
        $misTickets = Ticket::where('cliente_email', auth()->user()->email)
            ->orderBy('fecha_reporte', 'desc')
            ->take(5)->get();

        return view('usuario.dashboard', compact('misTickets'));
    }

    public function index()
    {
        $tickets = Ticket::where('cliente_email', auth()->user()->email)
            ->orderBy('fecha_reporte', 'desc')->get();

        return view('usuario.tickets.index', compact('tickets'));
    }

    public function create()
    {
        return view('usuario.tickets.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'descripcion_corta' => 'required|max:255',
            'categoria' => 'required|in:software,hardware,comunicaciones,plataformas,email,otro',
            'nivel_urgencia' => 'required|in:baja,media,alta,critica',
            'descripcion_detallada' => 'nullable',
            'departamento' => 'required|max:100',
            'attachments.*' => 'nullable|file|max:10240', // 10MB máx
        ]);

        $datos = $request->except('attachments');
        $datos['numero_reporte'] = 'TKT-' . date('Y') . '-' . str_pad(Ticket::count() + 1, 4, '0', STR_PAD_LEFT);
        $datos['cliente_nombre'] = auth()->user()->name;
        $datos['cliente_email'] = auth()->user()->email;
        $datos['fecha_reporte'] = now();
        $datos['status'] = 'pendiente';

        $ticket = Ticket::create($datos);

        // Procesar adjuntos si existen
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
            
            // Realizar análisis de IA si hay imágenes
            if (!empty($imagePaths)) {
                $this->performAIAnalysis($ticket, $imagePaths);
            }
        }

        return redirect()->route('usuario.tickets.index')
            ->with('success', 'Ticket creado exitosamente' . (!empty($imagePaths) ? ' con análisis de IA' : ''));
    }

    public function show(Ticket $ticket)
    {
        if ($ticket->cliente_email !== auth()->user()->email) {
            abort(403, 'No tienes acceso a este ticket.');
        }

        $ticket->load('attachments');
        return view('usuario.tickets.show', compact('ticket'));
    }

    /**
     * Realiza análisis de IA en las imágenes adjuntas
     */
    private function performAIAnalysis(Ticket $ticket, array $imagePaths)
    {
        try {
            $huggingFaceService = new HuggingFaceService();
            
            if (count($imagePaths) === 1) {
                // Análisis individual
                $result = $huggingFaceService->analyzeImage($imagePaths[0]);
                
                if ($result['success']) {
                    $ticket->ai_analysis = $result['analysis'];
                    $ticket->save();
                }
            } else {
                // Análisis múltiple
                $result = $huggingFaceService->analyzeMultipleImages($imagePaths);
                
                if ($result['success']) {
                    $ticket->ai_analysis = $result['combined_analysis'];
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
