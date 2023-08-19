<?php

namespace App\Http\Controllers;


use App\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image as ImageIntervention;

class ImageController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function imageResizeandUpload(Request $request)
    {


        $this->validate($request, [
            'image' => 'required|image|mimes:png,jpg|max:2048',
        ]);

        $image = $request->file('image');
        $input['imagename'] = time() . '.' . $image->extension();

        $destinationPath = public_path('uploads');

        $imageRecord = new Image();
        $imageRecord->filename = $input['imagename'];
        $imageRecord->path = $destinationPath;
        $imageRecord->save();
        $img = ImageIntervention::make($image->path());
        $img->resize(100, 100, function ($constraint) {
            $constraint->aspectRatio();
        })->save($destinationPath  . $input['imagename']);

        $destinationPath = public_path('/images');
        $image->move($destinationPath, $input['imagename']);
        return response()->json(['message' => 'Image uploaded and resized successfully']);
    }
}
