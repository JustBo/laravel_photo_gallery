<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Photo;

class PhotosController extends Controller
{
  public function create($album_id){
    return view('photos.create')->with(compact('album_id'));
  }
  public function store( Request $request ){
    $this->validate($request, [
      'title' => 'required',
      'photo' => 'image|max:20000'
    ]);
    $album_id = $request->input('album_id');
    // Get filename with extension
    $filenameWithExt = $request->file('photo')->getClientOriginalName();
    // Get file name
    $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
    // Get extension
    $extension = $request->file('photo')->getClientOriginalExtension();
    // Create new filename
    $filenameToStore = $filename.'_'.time().'.'.$extension;
    // Upload Image
    $path = $request->file('photo')->storeAs('public/photos/'.$album_id, $filenameToStore);
    //Create photo
    $photo = $request->input();
    $photo['photo'] = $filenameToStore;
    $photo['size']  = $request->file('photo')->getClientSize();
    $photo = Photo::create( $photo );
    return redirect('albums/'.$album_id)->with('success', 'Photo uploaded');
  }
}
