<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PesananController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pesanans = Pesanan::all();

        return response()->json([
            'data' => $pesanans
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
        $validator = Validator::make(
            $request->all(),
            [
                'nama' => 'required',
                'alamat' => 'required',
                'wilayah' => 'required',
            ],
        );

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $pesanan = Pesanan::create([
            'nama' => $request->nama,
            'alamat' => $request->alamat,
            'wilayah' => $request->wilayah,
        ]);

        return response()->json([
            'message' => 'Add Pesanan success',
            'data' => $pesanan
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Pesanan  $pesanan
     * @return \Illuminate\Http\Response
     */
    public function show($cari = null)
    {
        $data = Pesanan::query()
            ->where('nama', 'ilike', '%' . $cari . '%')
            ->orWhere('alamat', 'ilike', '%' . $cari . '%')
            ->orWhere('id', 'ilike', $cari)
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
            ], 200);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Pesanan  $pesanan
     * @return \Illuminate\Http\Response
     */
    public function edit(Pesanan $pesanan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Pesanan  $pesanan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Pesanan $pesanan)
    {
        $pesanan->nama = $request->nama;
        $pesanan->alamat = $request->alamat;
        $pesanan->wilayah = $request->wilayah;

        $pesanan->save();

        return response()->json([
            'data' => $pesanan,
            'message' => 'Update Berhasil'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Pesanan  $pesanan
     * @return \Illuminate\Http\Response
     */
    public function destroy(Pesanan $pesanan)
    {
        $pesanan->delete();
        return response()->json([
            'message' => 'Pesanan Berhasil didelete'
        ], 204);
    }
}