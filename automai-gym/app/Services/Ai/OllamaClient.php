<?php

namespace App\Services\Ai;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OllamaClient
{
    private string $baseUrl;
    private string $model;

    public function __construct()
    {
        $this->baseUrl = rtrim(env('OLLAMA_URL', 'http://localhost:11434'), '/');
        $this->model   = env('OLLAMA_MODEL', 'qwen3:8b');
    }

    /**
     * Envía un array de mensajes al modelo y devuelve el texto de la respuesta.
     *
     * @param  array  $messages  [['role' => 'user|assistant|system', 'content' => '...']]
     * @return string
     */
    public function chat(array $messages): string
    {
        try {
            $response = Http::timeout(300)->post("{$this->baseUrl}/api/chat", [
                'model'    => $this->model,
                'messages' => $messages,
                'stream'   => false,
                'options'  => [
                    'temperature' => 0.3, // más determinista para JSON
                ],
            ]);

            if ($response->failed()) {
                Log::error('[OllamaClient] Error HTTP: ' . $response->status() . ' - ' . $response->body());
                return '{"action":"reply","message":"Error interno al conectar con el modelo de IA. Inténtalo de nuevo."}';
            }

            $data = $response->json();
            return $data['message']['content'] ?? '{"action":"reply","message":"Sin respuesta del modelo."}';

        } catch (\Throwable $e) {
            Log::error('[OllamaClient] Exception: ' . $e->getMessage());
            return '{"action":"reply","message":"Error de conexión con Ollama: ' . addslashes($e->getMessage()) . '"}';
        }
    }
}
