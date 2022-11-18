<?php

namespace App\Http\Controllers;

use App\Models\Bookmark;
use Illuminate\Http\Request;

class BookmarkController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $bookmarks = Bookmark::all();

        return response()->json([
            'data' => $bookmarks
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
        
        $bookmark = Bookmark::create([
            'nama' => $request->nama,
            'alamat' => $request->alamat,
        ]);

        return response()->json([
            'data' => $bookmark
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Bookmark  $bookmark
     * @return \Illuminate\Http\Response
     */
    public function show($cari = null)
    {
        $data = Bookmark::query()
        ->where('nama', 'LIKE', $cari) 
        ->orWhere('alamat', 'LIKE', $cari) 
        ->orWhere('id', 'LIKE', $cari) 
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
            ],200);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Bookmark  $bookmark
     * @return \Illuminate\Http\Response
     */
    public function edit(Bookmark $bookmark)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Bookmark  $bookmark
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Bookmark $bookmark)
    {
        $bookmark->nama = $request->nama;
        $bookmark->alamat = $request->alamat;

        $bookmark->save();

        return response()->json([
            'data' => $bookmark
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Bookmark  $bookmark
     * @return \Illuminate\Http\Response
     */
    public function destroy(Bookmark $bookmark)
    {
        $bookmark->delete();
        return response()->json([
            'message' => 'Bookmark Berhasil didelete'
        ],204);
    }
}
