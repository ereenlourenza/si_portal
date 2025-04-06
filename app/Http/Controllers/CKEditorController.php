<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CKEditorController extends Controller
{
    public function __construct()
    {
        $this->middleware('checklevel:ADM');
    }

    public function upload(Request $request)
    {
        if ($request->hasFile('upload')) {
            $file = $request->file('upload');
            $originalName = preg_replace('/[^a-zA-Z0-9\._-]/', '_', $file->getClientOriginalName());
            $filename = time() . '_' . $originalName;
            $filepath = $file->storeAs('public/images/ckeditor', $filename);
            $url = asset('storage/images/ckeditor/' . $filename);
    
            return response()->json([
                'uploaded' => 1,
                'fileName' => $filename,
                'url' => $url
            ]);
        }
    
        return response()->json([
            'error' => [
                'message' => 'No file uploaded.'
            ]
        ], 400);
    }
    
}
