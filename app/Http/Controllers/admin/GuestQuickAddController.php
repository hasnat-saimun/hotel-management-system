<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\admin\GuestQuickAddRequest;
use App\Services\GuestQuickAddService;
use Illuminate\Http\JsonResponse;

class GuestQuickAddController extends Controller
{
    public function store(GuestQuickAddRequest $request, GuestQuickAddService $service): JsonResponse
    {
        $validated = $request->validated();
        $duplicateMatches = $service->findDuplicateMatches($validated);

        if (!empty($duplicateMatches)) {
            $mappedMatches = $service->mapDuplicateMatches($duplicateMatches);
            $primaryMatch = $mappedMatches[0] ?? null;

            return response()->json([
                'success' => false,
                'duplicate_found' => true,
                'message' => 'A matching guest already exists. Please use the existing guest instead of creating a duplicate.',
                'primary_guest' => $primaryMatch,
                'matches' => $mappedMatches,
                'match_count' => count($mappedMatches),
            ], 409);
        }

        $guest = $service->create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Guest created successfully.',
            'guest' => $service->mapGuest($guest),
        ], 201);
    }
}