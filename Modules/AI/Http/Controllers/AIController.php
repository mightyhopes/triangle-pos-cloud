<?php

namespace Modules\AI\Http\Controllers;

use Illuminate\Routing\Controller;
use Modules\AI\Services\AIService;

class AIController extends Controller
{
    protected $aiService;

    public function __construct(AIService $aiService)
    {
        $this->aiService = $aiService;
    }

    public function getDailyInsight(\Illuminate\Http\Request $request)
    {
        try {
            $days = $request->input('days', 1);
            $insight = $this->aiService->generateDailyInsight($days);
            return response()->json([
                'status' => 'success',
                'insight' => $insight
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
