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

    public function show($productId, $email)
    {
        $review = ReviewModel::with('user', 'product')
            ->where('productId', $productId)
            ->where('email', $email)
            ->firstOrFail();

        return new ReviewResource($review);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'productId' => 'required|exists:products,id',
            'email' => 'required|exists:users,email',
            'review' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        $review = ReviewModel::create($validated);

        return response()->json($review, 201);
    }

    public function update(Request $request, $productId, $email)
    {
        $review = ReviewModel::where('productId', $productId)
            ->where('email', $email)
            ->firstOrFail();

        $review->update($request->only('review', 'rating'));

        return $review;
    }

    public function destroy($productId, $email)
    {
        $review = ReviewModel::where('productId', $productId)
            ->where('email', $email)
            ->firstOrFail();

        $review->delete();

        return response()->json(['message' => 'Review deleted']);
    }
}
