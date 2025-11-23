<?php

namespace Modules\Sale\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\Sale\Entities\Sale;
use Modules\Sale\Entities\SaleDetails;

class DummySaleSeeder extends Seeder
{
    public function run()
    {
        $customer = \Modules\People\Entities\Customer::first();
        if (!$customer) {
            $customer = \Modules\People\Entities\Customer::create([
                'customer_name' => 'Test Customer',
                'customer_email' => 'test@example.com',
                'customer_phone' => '08123456789',
                'city' => 'Jakarta',
                'country' => 'Indonesia',
                'address' => 'Jl. Test No. 1'
            ]);
        }

        $sale = Sale::create([
            'date' => now(),
            'reference' => 'TEST-SALE-001',
            'customer_id' => $customer->id,
            'customer_name' => $customer->customer_name,
            'tax_percentage' => 0,
            'tax_amount' => 0,
            'discount_percentage' => 0,
            'discount_amount' => 0,
            'shipping_amount' => 0,
            'total_amount' => 100000,
            'paid_amount' => 100000,
            'due_amount' => 0,
            'status' => 'Completed',
            'payment_status' => 'Paid',
            'payment_method' => 'Cash',
            'note' => 'Test Transaction for Firebase'
        ]);

        $product = \Modules\Product\Entities\Product::first();
        if (!$product) {
            $product = \Modules\Product\Entities\Product::create([
                'product_name' => 'Test Product',
                'product_code' => 'TEST01',
                'product_quantity' => 100,
                'product_cost' => 50000,
                'product_price' => 100000,
                'product_unit' => 'PCS',
                'product_stock_alert' => 10,
                'category_id' => 1 // Assuming category 1 exists from seeder, if not we might need to create it too but let's hope
            ]);
        }

        SaleDetails::create([
            'sale_id' => $sale->id,
            'product_id' => $product->id,
            'product_name' => $product->product_name,
            'product_code' => $product->product_code,
            'quantity' => 1,
            'price' => $product->product_price,
            'unit_price' => $product->product_price,
            'sub_total' => $product->product_price,
            'product_discount_amount' => 0,
            'product_discount_type' => 'fixed',
            'product_tax_amount' => 0,
        ]);
    }
}
