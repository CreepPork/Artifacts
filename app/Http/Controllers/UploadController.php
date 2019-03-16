<?php

namespace App\Http\Controllers;

use App\Upload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['only' => ['update', 'destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Upload::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'zip' => 'required|file|mimes:zip|max:1024',
            'accessToken' => 'required|string'
        ]);

        if ($request->accessToken == env('ACCESS_TOKEN'))
        {
            $filePath = $request->file('zip')->store('public');

            Upload::create([
                'filename' => $request->file('zip')->hashName(),
                'original_filename' => $request->file('zip')->getClientOriginalName(),
                'path' => $filePath
            ]);
        }
        else
        {
            return response()->json([
                'accessToken' => 'The access token is invalid.'
            ], 403);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Upload  $upload
     * @return \Illuminate\Http\Response
     */
    public function show(Upload $upload)
    {
        return $upload;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Upload  $upload
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Upload $upload)
    {
        $request->validate([
            'zip' => 'required|file|mimes:zip|max:1024',
        ]);

        Storage::disk('public')->delete($upload->path);

        $path = $request->file('zip')->store('public');

        $fileName = $request->file('zip')->hashName();
        $originalFileName = $request->file('zip')->getClientOriginalName();

        $upload->update([
            'filename' => $fileName,
            'original_filename' => $originalFileName,
            'path' => $path
        ]);

        return response()->json($upload);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Upload  $upload
     * @return \Illuminate\Http\Response
     */
    public function destroy(Upload $upload)
    {
        $successful = $upload->delete();

        return response()->json(['success' => $successful]);
    }
}
