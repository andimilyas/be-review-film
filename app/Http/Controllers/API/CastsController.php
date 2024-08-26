<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Casts;
use App\Http\Requests\CastRequest;

class CastsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api','isAdmin'])->only('store', 'update', 'delete');
    }
    public function index()
    {
        $cast = Casts::all();

        return response()->json([
            "message" => "Tampil semua cast",
            "data" => $cast
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CastRequest $request)
    {
        Casts::create($request->all());

        return response()->json([
            "message" => "Cast berhasil ditambahkan"
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $cast = Casts::with('listMovies')->find($id);

        if(!$cast) {
            return response()->json([
                "message" => "id tidak ditemukan"
            ], 404);
        }

        return response()->json([
            "message" => "Data cast dengan id: $id",
            "data" => $cast
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $cast = Casts::find($id);

        if (!$cast) {
            return response()->json([
                "message" => "id tidak ditemukan"
            ], 404);
        }

        $cast->name = $request['name'];
        $cast->age = $request['age'];
        $cast->bio = $request['bio'];

        $cast->save();

        return response()->json([
            "message" => "Data cast dengan id: $id berhasil diupdate"
        ], 201);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $cast = Casts::find($id);

        if (!$cast) {
            return response()->json([
                "message" => "Id tidak ditemukan"
            ], 404);
        }

        $cast->delete();

        return response()->json([
            "message" => "Data cast dengan id: $id berhasil dihapus"
        ], 201);
    }
}
