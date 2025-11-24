<?php

use Modules\AI\Services\AIService;

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Testing Gemini AI Integration...\n";
echo "--------------------------------\n";

try {
    $service = new AIService();
    echo "Generating Daily Insight...\n";
    
    // Force date to yesterday for testing logic, or just rely on the service using yesterday
    // The service uses Carbon::yesterday()
    
    $insight = $service->generateDailyInsight();
    
    echo "\nResult:\n";
    echo $insight . "\n";
    
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
