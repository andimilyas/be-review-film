<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CastMovies;
use App\Http\Requests\CastMovieRequest;

class CastMoviesController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api','isAdmin'])->only('store', 'update', 'delete');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $castMovie = CastMovies::all();

        return response()->json([
            "message" => "Tampil semua cast movie",
            "data" => $castMovie
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CastMovieRequest $request)
    {
        CastMovies::create($request->all());

        return response()->json([
            "message" => "Cast movie berhasil ditambahkan"
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $castMovie = CastMovies::with('movie', 'cast')->find($id);

        if(!$castMovie) {
            return response()->json([
                "message" => "id tidak ditemukan"
            ], 404);
        }
        
        return response()->json([
            "message" => "Data cast dengan id: $id",
            "data" => $castMovie
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $castMovie = CastMovies::find($id);

        if (!$castMovie) {
            return response()->json([
                "message" => "id tidak ditemukan"
            ], 404);
        }

        $castMovie->name = $request['name'];
        $castMovie->cast_id = $request['cast_id'];
        $castMovie->movie_id = $request['movie_id'];

        $castMovie->save();

        return response()->json([
            "message" => "Data cast dengan id: $id berhasil diupdate"
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $castMovie = CastMovies::find($id);

        if (!$castMovie) {
            return response()->json([
                "message" => "Id tidak ditemukan"
            ], 404);
        }

        $castMovie->delete();

        return response()->json([
            "message" => "Data cast dengan id: $id berhasil dihapus"
        ], 201);
    }
}
