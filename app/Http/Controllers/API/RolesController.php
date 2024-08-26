<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Roles;
use App\Http\Requests\RoleRequest;

class RolesController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api','isAdmin'])->only('index','store', 'update', 'delete');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Roles::all();

        return response()->json([
            "message" => "Tampil semua role",
            "data" => $roles
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RoleRequest $request)
    {
        Roles::create($request->all());

        return response()->json([
            "message" => "Role berhasil ditambahkan"
        ],201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RoleRequest $request, string $id)
    {
        $roles = Roles::find($id);

        if (!$roles) {
            response()->json([
                "message" => "Id tidak ditemukan"
            ], 404);
        }

        $roles->name = $request['name'];

        $roles->save();

        return response()->json([
            "message" => "Data role dengan id: $id berhasil diupdate"
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $roles = Roles::find($id);

        if (!$roles) {
            response()->json([
                "message" => "Id tidak ditemukan"
            ], 404);
        }

        $roles->delete();

        return response()->json([
            "message" => "Data role dengan id: $id berhasil dihapus"
        ], 201);
    }
}
