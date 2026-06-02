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
        $guest = $service->create($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Guest created successfully.',
            'guest' => $service->mapGuest($guest),
        ], 201);
    }
}