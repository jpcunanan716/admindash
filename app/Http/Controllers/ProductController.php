<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Product::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $sortField = $request->get('sort_field', 'id');
        $sortDirection = $request->get('sort_direction', 'asc');

        $query->orderBy($sortField, $sortDirection);

        $perPage = $request->get('per_page', 10);
        $products = $query->paginate($perPage);

        $transformedProducts = $products->getCollection()->map(function($product) {
            $arr = $product->toArray();
            $arr['images'] = $product->images ? json_decode($product->images) : [];
            return $arr;
        });

        $products->setCollection($transformedProducts);

        return response()->json([
            'data' => $products->items(),
            'meta' => [
                'current_page'   => $products->currentPage(),
                'last_page'      => $products->lastPage(),
                'from'           => $products->firstItem(),
                'to'             => $products->lastItem(),
                'total'          => $products->total(),
                'per_page'       => $products->perPage(),
                'prev_page_url'  => $products->previousPageUrl(),
                'next_page_url'  => $products->nextPageUrl(),
            ]
        ]);
    }

    // Create a new product
   public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'description' => 'nullable|string',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'date' => 'required|date',
        ]);

        $dateTime = \Carbon\Carbon::parse($data['date'])
            ->setTimeFrom(\Carbon\Carbon::now());

        $product = Product::create([
            'name' => $data['name'],
            'category' => $data['category'],
            'description' => $data['description'],
            'dateTime' => $dateTime,
        ]);

        if ($request->hasFile('images')) {
            $paths = [];
            foreach ($request->file('images') as $image) {
                $paths[] = $image->store('products', 'public');
            }

            $product->images = json_encode($paths);
            $product->save();
        }

        return response()->json($product, 201);
    }

    // Update an existing product
    public function update(Request $request, Product $product): JsonResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'description' => 'nullable|string',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'date' => 'required|date',
            'existing_images' => 'nullable|string',
        ]);

        $dateTime = \Carbon\Carbon::parse($data['date'])
            ->setTimeFrom(\Carbon\Carbon::now());

        $product->update([
            'name' => $data['name'],
            'category' => $data['category'],
            'description' => $data['description'],
            'dateTime' => $dateTime,
        ]);

        $allImages = [];
        
        if ($request->filled('existing_images')) {
            $existingImages = json_decode($request->existing_images, true);
            if (is_array($existingImages)) {
                $allImages = $existingImages;
            }
        }
        
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $allImages[] = $image->store('products', 'public');
            }
        }
        
        $product->images = json_encode($allImages);
        $product->save();

        return response()->json($product);
    }

    public function show(Product $product): JsonResponse
    {
        $productArray = $product->toArray();

    $productArray['images'] = $product->images ? json_decode($product->images) : [];

    return response()->json($productArray);
    }

    // Delete a product
    public function destroy(Product $product): JsonResponse
    {
        $product->delete();

        return response()->json([
            'message' => 'Product deleted successfully'
        ]);
    }

    public function categories(): JsonResponse
    {
        $categories = Product::distinct()->pluck('category');
        return response()->json($categories);
    }
}
