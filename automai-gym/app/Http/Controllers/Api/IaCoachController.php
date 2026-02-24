<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ChatConversacion;
use App\Models\ChatMensajeIA;
use App\Services\Ai\AiCoachService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IaCoachController extends Controller
{
    private AiCoachService $service;

    public function __construct()
    {
        $this->service = new AiCoachService();
    }

    // -------------------------------------------------------------------------
    // POST /api/ia/chat
    // -------------------------------------------------------------------------

    public function chat(Request $request): JsonResponse
    {
        $request->validate([
            'message'         => 'required|string|max:2000',
            'conversation_id' => 'nullable|integer',
        ]);

        $user    = Auth::user();
        $message = $request->input('message');
        $convId  = $request->input('conversation_id');

        // Crear o recuperar conversación
        if ($convId) {
            $conv = ChatConversacion::where('id', $convId)
                ->where('id_usuario', $user->id_usuario)
                ->first();

            if (!$conv) {
                return response()->json(['error' => 'Conversación no encontrada.'], 404);
            }
        } else {
            // Nueva conversación — usar las primeras palabras del mensaje como título
            $titulo = mb_substr($message, 0, 50) . (mb_strlen($message) > 50 ? '…' : '');
            $conv   = ChatConversacion::create([
                'id_usuario' => $user->id_usuario,
                'titulo'     => $titulo,
            ]);
        }

        // Procesar mensaje
        $result = $this->service->handleMessage($user, $conv->id, $message);

        return response()->json([
            'conversation_id' => $conv->id,
            'message'         => $result['message'],
            'action'          => $result['action'],
            'tool_result'     => $result['tool_result'] ?? null,
        ]);
    }

    // -------------------------------------------------------------------------
    // GET /api/ia/conversations
    // -------------------------------------------------------------------------

    public function listConversations(): JsonResponse
    {
        $user = Auth::user();

        $conversations = ChatConversacion::where('id_usuario', $user->id_usuario)
            ->orderBy('updated_at', 'desc')
            ->limit(20)
            ->get(['id', 'titulo', 'created_at', 'updated_at']);

        return response()->json($conversations);
    }

    // -------------------------------------------------------------------------
    // POST /api/ia/conversations
    // -------------------------------------------------------------------------

    public function createConversation(Request $request): JsonResponse
    {
        $user = Auth::user();

        $conv = ChatConversacion::create([
            'id_usuario' => $user->id_usuario,
            'titulo'     => $request->input('titulo', 'Nueva conversación'),
        ]);

        return response()->json($conv, 201);
    }

    // -------------------------------------------------------------------------
    // GET /api/ia/conversations/{id}/messages?before=<id>&limit=30
    // -------------------------------------------------------------------------

    public function messagesPaginated(Request $request, int $conversationId): JsonResponse
    {
        $user   = Auth::user();

        $conv = ChatConversacion::where('id', $conversationId)
            ->where('id_usuario', $user->id_usuario)
            ->first();

        if (!$conv) {
            return response()->json(['error' => 'Conversación no encontrada.'], 404);
        }

        $limit  = min((int) $request->query('limit', 30), 100);
        $before = $request->query('before'); // ID de mensaje cursor

        $query = ChatMensajeIA::where('id_conversacion', $conversationId)
            ->where('rol', '!=', 'tool'); // ocultar mensajes internos de herramienta

        if ($before) {
            $query->where('id', '<', (int) $before);
        }

        $messages = $query->orderBy('id', 'desc')->limit($limit)->get()->reverse()->values();

        $hasMore = ChatMensajeIA::where('id_conversacion', $conversationId)
            ->where('rol', '!=', 'tool')
            ->when($before, fn ($q) => $q->where('id', '<', (int) $before))
            ->where('id', '<', ($messages->isNotEmpty() ? $messages->first()->id : PHP_INT_MAX))
            ->exists();

        return response()->json([
            'messages'   => $messages,
            'has_more'   => $hasMore,
            'oldest_id'  => $messages->isNotEmpty() ? $messages->first()->id : null,
        ]);
    }

    // -------------------------------------------------------------------------
    // DELETE /api/ia/conversations/{id}
    // -------------------------------------------------------------------------

    public function deleteConversation(int $id): JsonResponse
    {
        $user = Auth::user();

        $conv = ChatConversacion::where('id', $id)
            ->where('id_usuario', $user->id_usuario)
            ->first();

        if (!$conv) {
            return response()->json(['error' => 'Conversación no encontrada.'], 404);
        }

        // Borrar mensajes y la conversación
        ChatMensajeIA::where('id_conversacion', $id)->delete();
        $conv->delete();

        return response()->json(['success' => true]);
    }
}
