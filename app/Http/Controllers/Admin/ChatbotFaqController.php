<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChatbotFaq;
use Illuminate\Http\Request;

class ChatbotFaqController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $faqs = ChatbotFaq::latest()->paginate(15);
        
        return view('admin.chatbot.index', compact('faqs'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'question' => 'required|string|max:1000',
            'answer' => 'required|string|max:5000',
            'keywords' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        // Set default value untuk is_active jika tidak ada
        $validated['is_active'] = $request->has('is_active') ? true : false;

        // Convert keywords string menjadi array
        if (!empty($validated['keywords'])) {
            $keywords = array_map('trim', explode(',', $validated['keywords']));
            $validated['keywords'] = $keywords;
        } else {
            $validated['keywords'] = [];
        }

        ChatbotFaq::create($validated);

        return redirect()->route('admin.chatbot.index')
            ->with('success', 'FAQ berhasil ditambahkan!');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ChatbotFaq $chatbot)
    {
        $validated = $request->validate([
            'question' => 'required|string|max:1000',
            'answer' => 'required|string|max:5000',
            'keywords' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        // Set default value untuk is_active jika tidak ada
        $validated['is_active'] = $request->has('is_active') ? true : false;

        // Convert keywords string menjadi array
        if (!empty($validated['keywords'])) {
            $keywords = array_map('trim', explode(',', $validated['keywords']));
            $validated['keywords'] = $keywords;
        } else {
            $validated['keywords'] = [];
        }

        $chatbot->update($validated);

        return redirect()->route('admin.chatbot.index')
            ->with('success', 'FAQ berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ChatbotFaq $chatbot)
    {
        $chatbot->delete();

        return redirect()->route('admin.chatbot.index')->with('success', 'Pertanyaan berhasil dihapus!');
    }
}
