<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AIService;

class AIController extends Controller
{
    public function showForm()
    {
        return view('ai_form');
    }

    public function generate(Request $request, AIService $aiService)
    {
        $request->validate([
            'prompt' => 'required|string|min:5|max:255'
        ]);

        try {
            $response = $aiService->generateText($request->input('prompt'));
            return view('ai_form', ['output' => $response]);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'AI request failed: ' . $e->getMessage()]);
        }
    }
}
