<?php

namespace App\Http\Controllers;

use App\Http\Requests\Track\StoreRequest;
use App\Http\Traits\FilesTrait;
use App\Models\Track;
use Illuminate\Http\Request;

class TrackController extends Controller
{

    use FilesTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $tracks = Track::query();

        if ($request->input('artist')) {

            $tracks->where('artist_id', $request->input('artist'));
        }

        if ($request->input('album')) {

            $tracks->where('album_id', $request->input('album'));
        }

        return $tracks->get();
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {
        $track = Track::create($request->except('track') + $this->getFileNames($request));

        $this->storeFiles($request, ['track'], 'uploads/tracks');

        return $track;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Track  $track
     * @return \Illuminate\Http\Response
     */
    public function show(Track $track)
    {
        return $track->with(['album', 'artist'])->get();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Track  $track
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Track $track)
    {
        $track->fill($request->all());

        if ($request->file('track')) {

            $this->storeFiles($request, ['track'], 'uploads/tracks');

            $track->track = $this->getFileNames($request)['track'];
        }

        $track->save();

        return $track;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Track  $track
     * @return \Illuminate\Http\Response
     */
    public function destroy(Track $track)
    {
        return $track->delete();
    }
}
