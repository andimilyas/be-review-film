<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Profiles;
use App\Models\Users;
use Illuminate\Support\Facades\Validator;

class ProfilesController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'age' => 'required|integer',
            'address' => 'required|string',
            'biodata' => 'required|string',
        ]);
        
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $currentUser = auth()->user();

        $profile = Profiles::updateOrCreate(
            ['user_id' => $currentUser->id],
            [   'age' => $request['age'],
                'address' => $request['address'],
                'biodata' => $request['biodata'],
                'user_id' => $currentUser->id
            ]
        );

        return response()->json([
            "message" => "Tambah/update profile user berhasil",
            "data" => $profile
        ], 201);

    }
    public function index()
    {
        $currentUser = auth()->user();

        $profile = Profiles::with('user')->where('user_id',$currentUser->id)->first();

        return response()->json([
            "message" => "Tampil data profile",
            "data" => $profile
        ]);
    }
}
