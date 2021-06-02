<?php

namespace App\Http\Controllers;

use App\Http\Requests\Album\StoreRequest;
use App\Http\Traits\FilesTrait;
use App\Models\Album;
use Illuminate\Http\Request;

class AlbumController extends Controller
{

    use FilesTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Album::with(['artist'])->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {
        $album = Album::create($request->except(['cover']) + $this->getFileNames($request, '/uploads/albums/'));

        $this->storeFiles($request, ['cover'], 'uploads/albums');

        return $album;
    }



    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Album  $album
     * @return \Illuminate\Http\Response
     */
    public function show(Album $album)
    {
        return $album;
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Album  $album
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Album $album)
    {
        $album->fill($request->all());

        if ($request->file('cover')) {

            $this->storeFiles($request, ['cover'], 'uploads/albums');

            $album->cover = $this->getFileNames($request)['cover'];
        }

        $album->save();

        return $album;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Album  $album
     * @return \Illuminate\Http\Response
     */
    public function destroy(Album $album)
    {
        return $album->delete();
    }
}
