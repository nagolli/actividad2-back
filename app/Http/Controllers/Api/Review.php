<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\ReviewResource;
use App\Models\Review as ReviewModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class Review extends Controller
{
    public function index()
    {
        $reviews = ReviewModel::with('user', 'product')->get();
        return ReviewResource::collection($reviews);
    }

    public function indexByProduct($productId)
    {
    $reviews = ReviewModel::with('user')
        ->where('productId', $productId)
        ->get();

    return ReviewResource::collection($reviews);
    }
    
    public function show($productId, $userId)
    {
        $review = ReviewModel::with('user', 'product')
            ->where('productId', $productId)
            ->where('user_id', $userId)
            ->firstOrFail();

        return new ReviewResource($review);
    }

    public function averageRating($productId)
    {
        $average = ReviewModel::where('productId', $productId)->avg('rating');
        $average = $average === null ? null : round($average, 1);

        return response()->json($average);
    }

    public function store(Request $request)
    {
        // Validación de datos
        $validated = $request->validate([
            'productId' => 'required|exists:products,id',
            'user_id'   => 'required|exists:app_user,id', // reemplazamos email por user_id
            'review'    => 'required|string|max:1000',
            'rating'    => 'required|integer|min:1|max:5',
        ]);

        // No duplicar reviews de usuario
        $existingReview = ReviewModel::where('productId', $validated['productId'])
            ->where('user_id', $validated['user_id'])
            ->first();

        if ($existingReview) {
            return response()->json([
                'message' => 'El usuario ya ha hecho una reseña para este producto.'
            ], 409);
        }

        // Crear la review
        $review = ReviewModel::create($validated);
        return response()->json([
            'message' => 'Review creada correctamente',
            'data' => $review
        ], 201);
    }

    public function update(Request $request, $productId, $userId)
    {
        $review = ReviewModel::where('productId', $productId)
            ->where('user_id', $userId)
            ->firstOrFail();

        // sometimes hace que no falle si no viene el parametro (actualizacion parcial)
        $validated = $request->validate([
            'review' => 'sometimes|string',
            'rating' => 'sometimes|integer|min:1|max:5',
        ]);

        $review->update($validated);

        return new ReviewResource($review);
    }

    public function destroy($productId, $userId)
    {
        $review = ReviewModel::where('productId', $productId)
            ->where('user_id', $userId)
            ->firstOrFail();

        $review->delete();

        return response()->json(['message' => 'Review deleted']);
    }
}
