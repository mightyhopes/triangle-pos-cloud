<?php

namespace Modules\AI\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Modules\Sale\Entities\Sale;
use Modules\Sale\Entities\SaleDetails;
use Carbon\Carbon;

class AIService
{
    public function generateDailyInsight()
    {
        $apiKey = config('settings.gemini_api_key'); // We will add this config later
        if (!$apiKey) {
            return "Please configure your Gemini API Key in System Settings to get AI insights.";
        }

        // 1. Gather Data (Yesterday's Sales)
        $yesterday = Carbon::yesterday()->format('Y-m-d');
        $sales = Sale::whereDate('date', $yesterday)->with('saleDetails')->get();

        if ($sales->isEmpty()) {
            return "No sales data found for yesterday ($yesterday). Make some sales to get insights!";
        }

        $totalRevenue = $sales->sum('total_amount');
        $totalOrders = $sales->count();
        $topProducts = [];

        foreach ($sales as $sale) {
            foreach ($sale->saleDetails as $detail) {
                if (!isset($topProducts[$detail->product_name])) {
                    $topProducts[$detail->product_name] = 0;
                }
                $topProducts[$detail->product_name] += $detail->quantity;
            }
        }
        arsort($topProducts);
        $top3 = array_slice($topProducts, 0, 3);
        $top3String = implode(', ', array_keys($top3));

        // 2. Construct Prompt
        $prompt = "You are a business consultant for a retail store. 
        Here is the sales summary for yesterday ($yesterday):
        - Total Revenue: " . format_currency($totalRevenue) . "
        - Total Orders: $totalOrders
        - Top Selling Items: $top3String
        
        Analyze this performance and provide 1 short, actionable tip (max 2 sentences) for the owner to improve sales or operations tomorrow. 
        Focus on marketing, inventory, or staff motivation. Do not be generic.";

        // 3. Call Gemini API
        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateContent?key={$apiKey}", [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt]
                        ]
                    ]
                ]
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return $data['candidates'][0]['content']['parts'][0]['text'] ?? 'Could not generate insight.';
            } else {
                return "Error calling AI API: " . $response->status();
            }
        } catch (\Exception $e) {
            return "Exception: " . $e->getMessage();
        }
    }

    public function getUpsellSuggestions($cartProductIds)
    {
        if (empty($cartProductIds)) {
            return [];
        }

        // Simple Association Rule: "People who bought X also bought Y"
        // We look for past sales that contained ANY of the items in the cart
        // And find the most common OTHER items in those sales.

        $relatedSalesIds = SaleDetails::whereIn('product_id', $cartProductIds)
            ->select('sale_id')
            ->distinct()
            ->limit(100) // Limit for performance
            ->pluck('sale_id');

        if ($relatedSalesIds->isEmpty()) {
            // Fallback: Just return top selling items overall
            return DB::table('sale_details')
                ->select('product_id', 'product_name', DB::raw('count(*) as total'))
                ->whereNotIn('product_id', $cartProductIds)
                ->groupBy('product_id', 'product_name')
                ->orderByDesc('total')
                ->limit(3)
                ->get();
        }

        $suggestions = DB::table('sale_details')
            ->select('product_id', 'product_name', DB::raw('count(*) as frequency'))
            ->whereIn('sale_id', $relatedSalesIds)
            ->whereNotIn('product_id', $cartProductIds) // Don't suggest what's already in cart
            ->groupBy('product_id', 'product_name')
            ->orderByDesc('frequency')
            ->limit(3)
            ->get();

        return $suggestions;
    }
}
