<?php

namespace App\Http\Controllers;

use App\Services\StoreService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class StoreHourController extends Controller
{
    protected StoreService $storeService;

    public function __construct(StoreService $storeService)
    {
        $this->storeService = $storeService;
    }

    public function getStoreStatus(): JsonResponse
    {
        return response()->json($this->storeService->getStoreStatus());
    }

    public function getNextOpening(Request $request): JsonResponse
    {
        $date = $request->query('date');
        return response()->json($this->storeService->checkIfOpen($date));
    }
}
