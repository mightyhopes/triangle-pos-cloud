<?php

namespace Modules\Firebase\Services;

use Kreait\Firebase\Factory;
use Modules\Sale\Entities\Sale;
use Illuminate\Support\Facades\Log;

class FirebaseService
{
    protected $database;

    public function __construct()
    {
        $credentialsPath = storage_path('firebase_credentials.json');

        if (file_exists($credentialsPath)) {
            try {
                $factory = (new Factory)
                    ->withServiceAccount($credentialsPath)
                    ->withDatabaseUri(config('services.firebase.database_url'));

                $this->database = $factory->createDatabase();
            } catch (\Exception $e) {
                Log::error("Firebase Init Error: " . $e->getMessage());
            }
        }
    }

    public function syncSale(Sale $sale)
    {
        if (!$this->database) {
            return;
        }

        try {
            // Load relationships to send complete data
            $sale->load('saleDetails', 'customer');

            $data = [
                'id' => $sale->id,
                'date' => $sale->date,
                'reference' => $sale->reference,
                'customer' => $sale->customer_name,
                'total_amount' => $sale->total_amount,
                'status' => $sale->status,
                'payment_status' => $sale->payment_status,
                'items' => $sale->saleDetails->map(function ($item) {
                    return [
                        'product' => $item->product_name,
                        'quantity' => $item->quantity,
                        'price' => $item->unit_price
                    ];
                })->toArray(),
                'updated_at' => now()->toIso8601String()
            ];

            // Push to 'sales' node
            $this->database->getReference('sales/' . $sale->id)->set($data);
            
            // Also push to 'active_orders' if status is pending/ordered (for Kitchen Display)
            if ($sale->status != 'Completed') {
                $this->database->getReference('active_orders/' . $sale->id)->set($data);
            } else {
                $this->database->getReference('active_orders/' . $sale->id)->remove();
            }

        } catch (\Exception $e) {
            Log::error("Firebase Sync Error: " . $e->getMessage());
        }
    }
}
