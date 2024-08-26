<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Genres;
use App\Http\Requests\GenreRequest;

class GenresController extends Controller
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
        $genres = Genres::all();

        return response()->json([
            "message" => "Tampil semua genre",
            "data" => $genres
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(GenreRequest $request)
    {
        Genres::create($request->all());

        return response()->json([
            "message" => "Genre berhasil ditambahkan"
        ],201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $genre = Genres::with('listMovies')->find($id);

        if(!$genre) {
            return response()->json([
                "message" => "Id tidak ditemukan"
            ], 404);
        }
        
        return response()->json([
            "message" => "Data genre dengan id: $id",
            "data" => $genre
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(GenreRequest $request, string $id)
    {
        $genre = Genres::find($id);

        if (!$genre) {
            response()->json([
                "message" => "Id tidak ditemukan"
            ], 404);
        }

        $genre->name = $request['name'];

        $genre->save();

        return response()->json([
            "message" => "Data genre dengan id: $id berhasil diupdate"
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $genre = Genres::find($id);

        if (!$genre) {
            response()->json([
                "message" => "Id tidak ditemukan"
            ], 404);
        }

        $genre->delete();

        return response()->json([
            "message" => "Data genre dengan id: $id berhasil dihapus"
        ], 201);
    }
}
