<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();

        return response()->json([
            'data' => $users
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $newDate = date('Y-m-d H:i:s', strtotime($request->tglLahir));

        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' =>  'required',
            'nama' =>  'required',
            'email' =>  'required',
            'noHP' =>  'required',
            'tglLahir' =>  'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = User::create([
            'username' => $request->username,
            'password' => $request->password,
            'nama' => $request->nama,
            'email' => $request->email,
            'noHP' => $request->noHP,
            'tglLahir' => $newDate
        ]);
        return new UserResource(true, 'Data Pegawai Berhasil Ditambahkan!', $user);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show($cari = null)
    {

        $data = User::query()
        ->where('id', 'LIKE', $cari) 
        ->get();
        if(count($data) > 1) {
            return response()->json([
                'status' => 200,
                'error' => "false",
                'message' => '',
                'totaldata' => count($data),
                'data' => $data,
            ],200);
        }else if(count($data) == 1) {
            return response()->json([
                'status' => 200,
                'error' => "false",
                'message' => '',
                'totaldata' => count($data),
                'data' => $data,
            ],200);
        }else {
            return response()->json([
                'status' => 404,
                'error' => "true",
                'message' => 'Data not found',
                'data' => $data,
            ],404);
        }

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $newDate = date('Y-m-d H:i:s', strtotime($request->tglLahir));
        $user->nama = $request->nama;
        $user->username = $request->username;
        $user->password = $request->password;
        $user->email = $request->email;
        $user->noHP = $request->noHP;
        $user->tglLahir = $newDate;

        $user->save();

        return response()->json([
            'data' => $user,
            'message' => 'Update Success'
        ]);
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();
        return response()->json([
            'message' => 'User Berhasil didelete'
        ],204);
    }
}
