<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\SmartAssistantService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

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

        $message = $request->input('message');
        $url = $request->input('url', '');

        $passwordFlowReply = $this->handlePasswordResetFlow($request, $message);
        if ($passwordFlowReply !== null) {
            return response()->json(['reply' => $passwordFlowReply]);
        }

        $user = Auth::user();
        if (!$user) {
            return response()->json(['reply' => 'Vous devez etre connecte pour discuter avec l\'assistant.']);
        }

        $role = strtolower($user->role->value ?? $user->role ?? 'etudiant');

        $reply = $this->assistant->getResponse($message, $role, $url);

        return response()->json([
            'reply' => $reply
        ]);
    }

    private function handlePasswordResetFlow(Request $request, string $message): ?string
    {
        $normalized = Str::lower($message);
        $session = $request->session();

        if ($session->get('assistant.awaiting_reset_email')) {
            $email = $this->extractEmail($message);
            if (!$email) {
                return 'Merci de saisir une adresse e-mail valide pour recevoir le lien de reinitialisation.';
            }

            $validator = Validator::make(['email' => $email], [
                'email' => ['required', 'email', 'exists:users,email'],
            ]);

            if ($validator->fails()) {
                return 'Cette adresse e-mail est invalide ou introuvable. Pouvez-vous verifier et reessayer ?';
            }

            $status = Password::sendResetLink(['email' => $email]);
            $session->forget('assistant.awaiting_reset_email');

            if ($status === Password::RESET_LINK_SENT) {
                return 'Lien de reinitialisation envoye. Verifiez votre boite mail.';
            }

            return 'Echec lors de l\'envoi du lien de reinitialisation. Reessayez plus tard.';
        }

        if (Str::contains($normalized, [
            'forgot password',
            'reset password',
            'mot de passe oublie',
            'mot de passe oublie',
            'reinitialiser mot de passe',
            'reinitialiser mon mot de passe',
            'reinitialiser le mot de passe',
            'reinitialisation mot de passe',
        ])) {
            $session->put('assistant.awaiting_reset_email', true);
            return 'Bien sur. Quelle est votre adresse e-mail pour recevoir le lien de reinitialisation ?';
        }

        return null;
    }

    private function extractEmail(string $message): ?string
    {
        if (preg_match('/[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,}/i', $message, $matches)) {
            return $matches[0];
        }

        return null;
    }
}
