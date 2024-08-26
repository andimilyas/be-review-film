<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Reviews;
use Illuminate\Http\Request;
use App\Models\Users;
use Illuminate\Support\Facades\Validator;

class ReviewsController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'critic' => 'required',
            'rating' => 'required|integer',
            'movie_id' => 'required|exists:movies,id'
        ]);
        
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $currentUser = auth()->user();

        $review = Reviews::updateOrCreate(
            ['user_id' => $currentUser->id],
            [   'critic' => $request['critic'],
                'rating' => $request['rating'],
                'movie_id' => $request['movie_id'],
                'user_id' => $currentUser->id
            ]
        );

        return response()->json([
            "message" => "Tambah/update review user berhasil",
            "data" => $review
        ], 201);
    }
}
