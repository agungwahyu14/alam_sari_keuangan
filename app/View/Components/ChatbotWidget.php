<?php

namespace App\View\Components;

use App\Models\ChatbotFaq;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ChatbotWidget extends Component
{
    public $allQuestions;

    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        // Load semua FAQ aktif
        $this->allQuestions = ChatbotFaq::active()
            ->orderBy('created_at', 'desc')
            ->get(['id', 'question', 'answer']);
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.chatbot-widget');
    }
}
