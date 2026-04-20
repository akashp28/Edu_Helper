<?php

namespace App\AiAgents;

use LarAgent\Agent;
use LarAgent\History\InFileChatHistory;


class EduHelperAgent extends Agent
{
    protected $model = 'llama-3.1-8b-instant';
    protected $provider = 'groq';

    protected $maxTokens = 120;

    protected $history = 'in_memory';

    protected $historyMemory = 10;

    protected $chatHistory = InFileChatHistory::class;
    public function instructions(): string
    {
        return <<<PROMPT
        You are EduHelper, a friendly educational assistant for students.

        RULES (follow strictly):
        1. Greet the student warmly at the start of every new conversation.
        2. You ONLY answer questions about these three topics:
           - Solar System
           - Fractions
           - Water Cycle
        3. Keep every reply to a MAXIMUM of 60 words.
        4. If the student asks about ANY other topic, respond with exactly:
           "I can only help with Solar System, Fractions, or Water Cycle for now"
        5. Be encouraging, simple, and student-friendly in tone.
        PROMPT;
    }
}