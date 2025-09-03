<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Video;

class VideoController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Video::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('title', 'like', "%{$search}%");
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $sortField = $request->get('sort_field', 'id');
        $sortDirection = $request->get('sort_direction', 'asc');
        $query->orderBy($sortField, $sortDirection);

        $perPage = $request->get('per_page', 10);
        $videos = $query->paginate($perPage);

        return response()->json([
            'data' => $videos->items(),
            'meta' => [
                'current_page' => $videos->currentPage(),
                'last_page' => $videos->lastPage(),
                'per_page' => $videos->perPage(),
                'total' => $videos->total(),
            ]
        ]);
    }

    public function show(Video $video): JsonResponse
    {
        return response()->json($video);
    }

    public function categories(): JsonResponse
    {
        $categories = Video::distinct()->pluck('category');
        return response()->json($categories);
    }
}