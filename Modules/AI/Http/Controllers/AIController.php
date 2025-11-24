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

    public function getDailyInsight()
    {
        try {
            $insight = $this->aiService->generateDailyInsight();
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
