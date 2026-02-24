<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class IaCoachController extends Controller
{
    public function chat(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        $message = $request->input('message');

        $systemPrompt = "Eres AutomAI Assistant, el entrenador impulsado por IA del gimnasio.
Tu tono es motivador, directo, conciso y profesional, siempre enfocado en el fitness.
Estás integrado dentro del dashboard del gimnasio de los usuarios.
Tus respuestas deben ser cortas, directas al grano, y con un lenguaje ameno.
Si el usuario te pregunta por rutinas semanales o clases, guíales a que pueden consultar esas secciones en el panel lateral, o diles de forma muy clara cómo deben entrenar.";

        $ollamaUrl = env('OLLAMA_URL', 'http://127.0.0.1:11434');
        $ollamaModel = env('OLLAMA_MODEL', 'llama3.2');

        try {
            // Se asume que ollama expone la API de chat: /api/chat
            $response = Http::timeout(60)->post("{$ollamaUrl}/api/chat", [
                'model' => $ollamaModel,
                'messages' => [
                    ['role' => 'system', 'content' => $systemPrompt],
                    ['role' => 'user', 'content' => $message],
                ],
                'stream' => false,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return response()->json([
                    'reply' => $data['message']['content'] ?? 'No tengo palabras. Sigue entrenando.'
                ]);
            }

            return response()->json(['reply' => 'Hubo un error contactando a mis servidores de IA. Inténtalo luego.'], 500);

        } catch (\Exception $e) {
            return response()->json(['reply' => 'No se pudo conectar a la IA. Asegúrate de que el contenedor de Ollama esté ejecutándose.'], 500);
        }
    }
}
