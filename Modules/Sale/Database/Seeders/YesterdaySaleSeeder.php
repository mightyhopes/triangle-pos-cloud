<?php

namespace Modules\Sale\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Sale\Entities\Sale;
use Modules\Sale\Entities\SaleDetails;
use Carbon\Carbon;

class YesterdaySaleSeeder extends Seeder
{
    public function run()
    {
        $yesterday = Carbon::yesterday();
        
        // 1. Create Categories & Products
        $categories = [
            'Minuman' => [
                'code' => 'MIN', 
                'items' => ['Kopi Susu Gula Aren', 'Ice Americano', 'Matcha Latte', 'Teh Tarik']
            ],
            'Makanan Ringan' => [
                'code' => 'MAR', 
                'items' => ['Roti Bakar Coklat', 'Kentang Goreng', 'Pisang Keju']
            ],
            'Makanan Berat' => [
                'code' => 'MAB', 
                'items' => ['Nasi Goreng Spesial', 'Mie Goreng Jawa', 'Ayam Geprek']
            ]
        ];

        $products = [];
        
        foreach ($categories as $catName => $data) {
            $category = \Modules\Product\Entities\Category::firstOrCreate(
                ['category_name' => $catName],
                ['category_code' => $data['code']]
            );

            foreach ($data['items'] as $itemName) {
                $product = \Modules\Product\Entities\Product::firstOrCreate(
                    ['product_name' => $itemName],
                    [
                        'product_code' => strtoupper(substr($itemName, 0, 3)) . rand(10, 99),
                        'product_quantity' => 100,
                        'product_cost' => 10000,
                        'product_price' => rand(15, 35) * 1000,
                        'product_unit' => 'Porsi',
                        'product_stock_alert' => 10,
                        'category_id' => $category->id
                    ]
                );
                $products[] = $product;
            }
        }

        // 2. Create Customer
        $customer = \Modules\People\Entities\Customer::firstOrCreate(
            ['customer_email' => 'pelanggan@setia.com'],
            [
                'customer_name' => 'Pelanggan Setia',
                'customer_phone' => '08123456789',
                'city' => 'Jakarta',
                'country' => 'Indonesia',
                'address' => 'Jl. Raya No. 1'
            ]
        );

        // 3. Generate Random Sales for Yesterday
        // Simulate a busy day: 15 transactions
        for ($i = 0; $i < 15; $i++) {
            // Pick random products (1 to 3 items per sale)
            $numItems = rand(1, 3);
            $saleProducts = [];
            $totalAmount = 0;

            for ($j = 0; $j < $numItems; $j++) {
                $prod = $products[array_rand($products)];
                $qty = rand(1, 2);
                $subTotal = $prod->product_price * $qty;
                
                $saleProducts[] = [
                    'product_id' => $prod->id,
                    'product_name' => $prod->product_name,
                    'product_code' => $prod->product_code,
                    'quantity' => $qty,
                    'price' => $prod->product_price,
                    'unit_price' => $prod->product_price,
                    'sub_total' => $subTotal,
                    'product_discount_amount' => 0,
                    'product_discount_type' => 'fixed',
                    'product_tax_amount' => 0,
                ];
                $totalAmount += $subTotal;
            }

            $sale = Sale::create([
                'date' => $yesterday->copy()->addHours(rand(9, 21)), // Random time between 9 AM - 9 PM
                'reference' => 'SALE-YEST-' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'customer_id' => $customer->id,
                'customer_name' => $customer->customer_name,
                'tax_percentage' => 0,
                'tax_amount' => 0,
                'discount_percentage' => 0,
                'discount_amount' => 0,
                'shipping_amount' => 0,
                'total_amount' => $totalAmount,
                'paid_amount' => $totalAmount,
                'due_amount' => 0,
                'status' => 'Completed',
                'payment_status' => 'Paid',
                'payment_method' => ['Cash', 'QRIS', 'Debit'][rand(0, 2)],
                'note' => 'Simulated Transaction'
            ]);

            foreach ($saleProducts as $sp) {
                $sp['sale_id'] = $sale->id;
                SaleDetails::create($sp);
            }
        }
    }
}
