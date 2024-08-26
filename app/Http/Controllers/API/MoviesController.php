<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Movies;
use App\Http\Requests\MovieRequest;
use Illuminate\Support\Facades\Storage;

class MoviesController extends Controller
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
        $movie = Movies::all();

        return response()->json([
            "message" => "Tampil semua film",
            "data" => $movie
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(MovieRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('poster')) {

            // membuat unique name pada gambar yang diinput

            $imageName = time().'.'.$request->poster->extension();

            // simpan gambar pada file storage

            $request->poster->storeAs('public/images', $imageName);

            //mengatur data gambar dengan nama pathnya

            $path = env('APP_URL').'/storage/images/';

            // menganti request nilai request image menjadi $imageName yang baru bukan berdasarkan request

            $data['poster'] = $path.$imageName;
        }
        
        Movies::create($data);
        
        return response()->json([
            "message" => "Data berhasil ditambahkan"
        ], 201);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //masukan nama fungsi relasi
        $movie = Movies::with('genre', 'listReview')->find($id);

        if (!$movie) {
            return response()->json([
                "message" => "Id tidak ditemukan"
            ], 404);
        }
        return response()->json([
            "message" => "Data film dengan id: $id",
            "data" => $movie
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(MovieRequest $request, string $id)
    {
        $data = $request->validated();

        $movieData = Movies::find($id);

        if (!$movieData) {
            return response()->json([
                "message" => "Id tidak ditemukan"
            ], 404);
        }

        if ($request->hasFile('poster')) {

            // Hapus gambar lama jika ada

            if ($movieData->poster) {
                $namePoster = basename($movieData->poster);
                Storage::delete('public/images/' . $namePoster);
            }

            //membuat unique name untuk gambar yang baru

            $imageName = time().'-poster.'.$request->poster->extension();

            $request->poster->storeAs('public/images', $imageName);

            $path = env('APP_URL').'/storage/images/';

            // menganti request nilai request image menjadi $imageName yang baru bukan berdasarkan request

            $data['poster'] = $path.$imageName;

        }

        $movieData->update($data);

        return response()->json([
            "message" => "Data berhasil diupdate"
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $movieData = Movies::find($id);

        if (!$movieData) {
            response()->json([
                "message" => "Id tidak ditemukan"
            ], 404);
        }

        if ($movieData->poster) {
            $namePoster = basename($movieData->poster);
            Storage::delete('public/images/' . $namePoster);
        }

        $movieData->delete();

        return response()->json([
            "message" => "Data berhasil dihapus"
        ], 201);
    }
}
