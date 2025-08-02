<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Services\AIService;

try {
    echo "Testing AI Service...\n";
    $aiService = new AIService();
    $result = $aiService->generateText("Hello AI");
    echo "Result: " . $result . "\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
