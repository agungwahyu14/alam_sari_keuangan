<?php

namespace App\Http\Controllers;

use App\Services\ChatbotService;
use Illuminate\Http\Request;

class ChatbotController extends Controller
{
    protected $chatbotService;

    public function __construct(ChatbotService $chatbotService)
    {
        $this->chatbotService = $chatbotService;
    }

    /**
     * Handle incoming chat message
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendMessage(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:500',
        ]);

        $userMessage = $request->input('message');
        $response = $this->chatbotService->findAnswer($userMessage);

        return response()->json([
            'user_message' => $userMessage,
            'bot_response' => $response['answer'],
            'matched_question' => $response['question'],
            'confidence' => $response['confidence'],
            'timestamp' => now()->format('H:i'),
        ]);
    }
}
