<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Gallery;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class HomeGalleryController extends Controller
{

    protected $baseUrl;    
    protected $imgUrl;    

    public function __construct()
    {
        $this->imgUrl = config('app.api_url');
        $this->imgUrl = config('app.img_url');
    }

    // 1. Index - Get all gallery items
    public function index()
    {
        $galleries = Gallery::all();
    
        // Transform the galleries data
        $formattedGalleries = $galleries->map(function ($gallery) {
            return [
                'id' => $gallery->id,
                'imgHeading' => $gallery->imgHeading,
                'image' => $this->imgUrl.'/'. $gallery->image,
                // Add any other fields you want to include here
            ];
        });
    
        return response()->json([
            'message' => 'Galleries retrieved successfully',
            'data' => $formattedGalleries,
        ], 200);
    }
    

    // 2. Show - Get a specific gallery item by ID
    public function show($id)
    {
        $gallery = Gallery::find($id);

        if ($gallery) {
            return response()->json($gallery, 200);
        } else {
            return response()->json(['message' => 'Gallery item not found'], 404);
        }
    }

    // 3. Store - Create a new gallery item
    public function store(Request $request)
    {
        // Use Validator for validation
        $validator = Validator::make($request->all(), [
            'imgHeading' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Validate image type and size
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        // Handle file upload
        if ($request->hasFile('image')) {
            // Store the image in a public directory and get its path
            $imagePath = $request->file('image')->store('gallery_images', 'public');

            // Create a new gallery record
            $gallery = Gallery::create([
                'imgHeading' => $request->input('imgHeading'),
                'image' => $imagePath,
            ]);

            return response()->json([
                'message' => 'Gallery item added successfully!',
                'gallery' => $gallery,
            ], 201);
        }

        return response()->json(['message' => 'Failed to upload image!'], 400);
    }

    public function update(Request $request, $id)
    {
        $gallery = Gallery::find($id);
    
        if (!$gallery) {
            return response()->json(['message' => 'Gallery item not found'], 404);
        }
    
        // Use Validator for validation
        $validator = Validator::make($request->all(), [
            'imgHeading' => 'sometimes|required|string|max:255',
            'image' => 'sometimes|required|image|mimes:jpeg,png,jpg,gif|max:200048', // Validate image type and size
        ]);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }
    
        // Update imgHeading if provided
        if ($request->has('imgHeading')) {
            $gallery->imgHeading = $request->input('imgHeading');
        }
    
        // Handle file upload if image is provided
        if ($request->hasFile('image')) {
            // Check if the gallery has an old image and delete it
            if ($gallery->image && Storage::disk('public')->exists($gallery->image)) {
                Storage::disk('public')->delete($gallery->image);
            }
    
            // Store the new image and update the path
            $imagePath = $request->file('image')->store('gallery_images', 'public');
            $gallery->image = $imagePath;
        }
    
        // Save the updated gallery item in the database
        $gallery->save();
    
        return response()->json([
            'message' => 'Gallery item updated successfully!',
            'gallery' => $gallery,
        ], 200);
    }
    

    // 5. Delete - Delete a specific gallery item by ID
    public function destroy($id)
    {
        $gallery = Gallery::find($id);

        if (!$gallery) {
            return response()->json(['message' => 'Gallery item not found'], 404);
        }

        // Delete the image file from storage
        if ($gallery->image && Storage::disk('public')->exists($gallery->image)) {
            Storage::disk('public')->delete($gallery->image);
        }

        // Delete the gallery item
        $gallery->delete();

        return response()->json(['message' => 'Gallery item deleted successfully!'], 200);
    }
}
