<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    echo "Checking Database...\n";
    $sale = \Modules\Sale\Entities\Sale::first();
    
    if (!$sale) {
        echo "Database connected, but NO SALES found.\n";
        echo "Please create a dummy sale in the database or POS first.\n";
    } else {
        echo "Found Sale ID: " . $sale->id . "\n";
        echo "Attempting to sync to Firebase...\n";
        
        (new \Modules\Firebase\Services\FirebaseService)->syncSale($sale);
        
        echo "Sync method called successfully.\n";
        echo "Check your Firebase Console now!\n";
    }
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}
