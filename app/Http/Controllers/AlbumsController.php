<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Album;

class AlbumsController extends Controller
{
  public function index(){
    $albums = Album::with('Photos')->get();
    return view('albums.index')->with( compact('albums') );
  }
  public function create(){
    return view('albums.create');
  }
  public function store( Request $request ){
    $this->validate($request, [
      'name' => 'required',
      'cover_image' => 'image|max:20000'
    ]);
    // Get filename with extension
    $filenameWithExt = $request->file('cover_image')->getClientOriginalName();
    // Get file name
    $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
    // Get extension
    $extension = $request->file('cover_image')->getClientOriginalExtension();
    // Create new filename
    $filenameToStore = $filename.'_'.time().'.'.$extension;
    // Upload Image
    $path = $request->file('cover_image')->storeAs('public/album_covers', $filenameToStore);
    //Create album
    $album = $request->input();
    $album['cover_image'] = $filenameToStore;
    $album = Album::create( $album );
    return redirect('/albums')->with('success', 'Album created');
  }

  public function show($id){
    $album = Album::with('Photos')->findOrFail($id);
    return view('albums.show')->with(compact('album'));
  }
}
