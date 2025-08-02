<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AIService
{
    private string $apiKey;
    private string $apiUrl;
    private string $model;

    public function __construct()
    {
        $this->apiKey = config('services.openai.key');
        $this->apiUrl = config('services.openai.url');
        $this->model = config('services.openai.model');
    }

    public function generateText(string $prompt): string
    {
        try {
            // Validate API key
            if (empty($this->apiKey)) {
                throw new \Exception('OpenAI API key is not configured. Please set OPENAI_API_KEY in your .env file.');
            }

            // Prepare the request data
            $requestData = [
                'model' => $this->model,
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are a helpful blog content generator. Create engaging, informative blog posts based on the given prompts. Format your response with proper paragraphs and structure.'
                    ],
                    [
                        'role' => 'user',
                        'content' => "Write a blog post about: {$prompt}"
                    ]
                ],
                'max_tokens' => 1000,
                'temperature' => 0.7
            ];

            // Make the API request
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->timeout(30)->post($this->apiUrl . '/chat/completions', $requestData);

            // Check if request was successful
            if (!$response->successful()) {
                Log::error('OpenAI API Error', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                throw new \Exception('OpenAI API request failed with status: ' . $response->status());
            }

            $data = $response->json();

            // Extract the generated content
            if (isset($data['choices'][0]['message']['content'])) {
                return trim($data['choices'][0]['message']['content']);
            } else {
                throw new \Exception('Unexpected response format from OpenAI API');
            }

        } catch (\Exception $e) {
            Log::error('AI Service Error', [
                'message' => $e->getMessage(),
                'prompt' => $prompt
            ]);
            
            // Return a fallback response
            return "Error generating content: " . $e->getMessage() . "\n\nPlease check your OpenAI API configuration and try again.";
        }
    }
}
