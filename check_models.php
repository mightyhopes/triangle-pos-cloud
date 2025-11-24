<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$apiKey = config('services.gemini.api_key');

echo "Checking available Gemini models...\n";
echo "API Key: " . substr($apiKey, 0, 5) . "...\n";

try {
    $response = Illuminate\Support\Facades\Http::get("https://generativelanguage.googleapis.com/v1beta/models?key={$apiKey}");
    
    if ($response->successful()) {
        $models = $response->json()['models'] ?? [];
        foreach ($models as $model) {
            echo "- " . $model['name'] . " (" . implode(', ', $model['supportedGenerationMethods']) . ")\n";
        }
    } else {
        echo "Error: " . $response->status() . " - " . $response->body();
    }
} catch (\Exception $e) {
    echo "Exception: " . $e->getMessage();
}
