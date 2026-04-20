<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AiAgents\EduHelperAgent;

class ChatController extends Controller
{
    private function getAgentKey(Request $request): string
    {
        if (!$request->session()->has('agent_chat_key')) {
            $request->session()->put('agent_chat_key', uniqid('edu_', true));
        }
        return $request->session()->get('agent_chat_key');
    }

    public function index()
    {
        return view('chat');
    }

    public function chat(Request $request)
    {
        $request->validate(['message' => 'required|string|max:500']);

        try {
            $response = EduHelperAgent::for($this->getAgentKey($request))
                ->message($request->input('message'))
                ->respond();

            return response()->json(['success' => true, 'reply' => $response]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'reply' => $e->getMessage()], 500);
        }
    }

    public function reset(Request $request)
    {
        $request->session()->put('agent_chat_key', uniqid('edu_', true));
        return response()->json(['success' => true]);
    }
}