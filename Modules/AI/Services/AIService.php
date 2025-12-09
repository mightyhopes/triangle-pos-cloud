<?php

namespace Modules\AI\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Modules\Sale\Entities\Sale;
use Modules\Sale\Entities\SaleDetails;
use Carbon\Carbon;

class AIService
{
    public function generateDailyInsight($days = 1)
    {
        $apiKey = config('services.gemini.api_key');
        if (!$apiKey) {
            return "Please configure your Gemini API Key in System Settings to get AI insights.";
        }

        // 1. Gather Data (Last X Days)
        $endDate = Carbon::yesterday();
        $startDate = Carbon::yesterday()->subDays($days - 1);
        
        $sales = Sale::whereBetween('date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
            ->with('saleDetails')
            ->get();

        if ($sales->isEmpty()) {
            return "No sales data found for the last $days days (" . $startDate->format('d M') . " - " . $endDate->format('d M') . "). Make some sales to get insights!";
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
        $periodText = ($days == 1) ? "yesterday (" . $endDate->format('Y-m-d') . ")" : "the last $days days (" . $startDate->format('Y-m-d') . " to " . $endDate->format('Y-m-d') . ")";
        
        $prompt = "You are a business consultant for a retail store. 
        Here is the sales summary for $periodText:
        - Total Revenue: " . format_currency($totalRevenue) . "
        - Total Orders: $totalOrders
        - Top Selling Items: $top3String
        
        Analyze this performance and provide 1 short, actionable tip (max 2 sentences) for the owner to improve sales or operations. 
        Focus on marketing, inventory, or staff motivation. Do not be generic.
        IMPORTANT: Answer in Indonesian language (Bahasa Indonesia) with a professional yet encouraging tone.";

        // 3. Call Gemini API
        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash-001:generateContent?key={$apiKey}", [
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
                return "Error calling AI API: " . $response->status() . " - " . $response->body();
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
    public function getAvailableModels()
    {
        $apiKey = config('services.gemini.api_key');
        if (!$apiKey) {
            return ['error' => 'API Key not configured'];
        }

        try {
            $response = Http::get("https://generativelanguage.googleapis.com/v1beta/models?key={$apiKey}");
            return $response->json();
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }
}
