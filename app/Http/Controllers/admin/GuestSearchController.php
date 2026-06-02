<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\admin\GuestSearchRequest;
use App\Services\GuestSearchService;
use Illuminate\Http\JsonResponse;

class GuestSearchController extends Controller
{
    public function search(GuestSearchRequest $request, GuestSearchService $service): JsonResponse
    {
        $validated = $request->validated();
        $paginator = $service->search($validated);
        $payload = $service->mapPaginator($paginator);

        return response()->json([
            'success' => true,
            'message' => $paginator->total() > 0
                ? 'Guests retrieved successfully.'
                : 'No guests found.',
            'data' => $payload['items'],
            'meta' => [
                'search_mode' => $service->searchMode($validated),
                'query' => [
                    'q' => $validated['q'] ?? null,
                    'name' => $validated['name'] ?? null,
                    'phone' => $validated['phone'] ?? null,
                    'email' => $validated['email'] ?? null,
                ],
                'pagination' => $payload['pagination'],
                'count' => $paginator->count(),
            ],
        ]);
    }
}