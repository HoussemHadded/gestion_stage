<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\SmartAssistantService;
use Illuminate\Support\Facades\Auth;

class AssistantController extends Controller
{
    private SmartAssistantService $assistant;

    public function __construct(SmartAssistantService $assistant)
    {
        $this->assistant = $assistant;
    }

    public function chat(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
            'url' => 'nullable|string'
        ]);

        $user = Auth::user();
        if (!$user) {
            return response()->json(['reply' => 'Vous devez être connecté pour discuter avec l\'assistant.']);
        }

        $message = $request->input('message');
        $url = $request->input('url', '');
        $role = strtolower($user->role->value ?? $user->role ?? 'etudiant');
        
        $reply = $this->assistant->getResponse($message, $role, $url);

        return response()->json([
            'reply' => $reply
        ]);
    }
}
