<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;

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

        $validator = Validator::make(
            $request->all(),
            [
                'username' => 'required',
                'password' =>  'required',
                'nama' =>  'required',
                'email' =>  'required|email:rfc,dns|unique:users',
                'noHP' =>  'required|min:6',
                'tglLahir' =>  'required'
            ],
            [
                'username.required' => 'Username masih kosong!',
                'password.required' => 'Password masih kosong!',
                'nama.required' => 'Nama masih kosong!',
                'email.required' => 'Email masih kosong!',
                'noHP.required' => 'Nomor HP masih kosong!',
                'noHP.min' => 'Nomor HP minimal 6 digit!',
                'tglLahir.required' => 'Tanggal lahir masih kosong!',
            ]

        );

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = User::create([
            'username' => $request->username,
            'password' => bcrypt($request->password),
            'nama' => $request->nama,
            'email' => $request->email,
            'noHP' => $request->noHP,
            'tglLahir' => $newDate
        ]);

        event(new Registered($user));
        auth()->login($user);

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
        if (count($data) > 1) {
            return response()->json([
                'status' => 200,
                'error' => "false",
                'message' => '',
                'totaldata' => count($data),
                'data' => $data,
            ], 200);
        } else if (count($data) == 1) {
            return response()->json([
                'status' => 200,
                'error' => "false",
                'message' => '',
                'totaldata' => count($data),
                'data' => $data,
            ], 200);
        } else {
            return response()->json([
                'status' => 404,
                'error' => "true",
                'message' => 'Data not found',
                'data' => $data,
            ], 404);
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
        ], 204);
    }

    public function login(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'username' => 'required',
                'password' =>  'required',
            ],
            [
                'username.required' => 'Username masih kosong!',
                'password.required' => 'Password masih kosong!',
            ]
        );

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = User::query()
            ->where('username', $request->username)
            ->first();

        if (!Auth::attempt($request->all())) {
            return response(['message' => 'Invalid Credential'], 401);
        }

        if ($user->email_verified_at == null) {
            return response(['message' => 'Verifikasi Email Terlebih dahulu'], 401);
        }

        if (!$user) {
            return response()->json([
                'message' => 'Username atau Password Salah !'
            ], 401);
        }

        return response()->json([
            'data' => $user,
            'success' => true,
            'message' => 'Anda Berhasil Login!'
        ]);
    }
}