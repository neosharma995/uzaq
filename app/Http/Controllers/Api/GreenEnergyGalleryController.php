<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\GreenEnergyGallery;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class GreenEnergyGalleryController extends Controller
{
    protected $baseUrl;
    protected $imgUrl;

    public function __construct()
    {
        $this->baseUrl = config('app.api_url'); // Fetching API base URL from config
        $this->imgUrl = config('app.img_url');  // Fetching image URL from config
    }

    // Get all records
    public function index()
    {
        // Fetch only the required fields from the GreenEnergyGallery model
        $galleries = GreenEnergyGallery::all(['id', 'heading', 'image']);

        // Modify the image path to include the full URL
        $galleries = $galleries->map(function ($gallery) {
            // Check if image exists, if yes, prepend the base URL
            $gallery->image = $gallery->image ? $this->imgUrl . '/' . $gallery->image : null;
            return $gallery;
        });

        // Return the response with a message and the data
        return response()->json([
            'message' => 'Green Energy Gallery records retrieved successfully',
            'data' => $galleries
        ], 200);
    }


    // Store a new gallery item


    public function store(Request $request)
    {
        // Custom validation using Validator
        $validator = Validator::make($request->all(), [
            'heading' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:204800',
        ]);

        // Check if the validation fails
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422); // Return validation errors
        }

        // Handle file upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images', 'public'); // Save to 'public/images'
        }

        // Create a new record
        $gallery = new GreenEnergyGallery();
        $gallery->heading = $request->heading;
        $gallery->image = $imagePath; // Store the image path
        $gallery->save();

        return response()->json(['message' => 'Green Energy Gallery added successfully!', 'data' => $gallery], 201);
    }


    // Show a single gallery item by ID
    public function show($id)
    {
        $gallery = GreenEnergyGallery::find($id);

        if (!$gallery) {
            return response()->json(['message' => 'Gallery item not found'], 404);
        }

        return response()->json($gallery);
    }

    // Update an existing gallery item by ID
    public function update(Request $request, $id)
    {
        // Custom validation using Validator
        $validator = Validator::make($request->all(), [
            'heading' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Check if the validation fails
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422); // Return validation errors
        }

        // Find the gallery item by ID
        $gallery = GreenEnergyGallery::find($id);

        if (!$gallery) {
            return response()->json(['message' => 'Gallery item not found'], 404);
        }

        // Handle file upload if a new image is uploaded
        if ($request->hasFile('image')) {
            // Delete the old image if it exists
            if ($gallery->image && Storage::exists('public/' . $gallery->image)) {
                Storage::delete('public/' . $gallery->image);
            }

            // Store the new image
            $imagePath = $request->file('image')->store('images', 'public');
            $gallery->image = $imagePath;
        }

        // Update heading
        $gallery->heading = $request->heading;
        $gallery->save();

        return response()->json(['message' => 'Green Energy Gallery updated successfully!', 'data' => $gallery], 200);
    }


    // Delete a gallery item by ID
    public function destroy($id)
    {
        $gallery = GreenEnergyGallery::find($id);

        if (!$gallery) {
            return response()->json(['message' => 'Gallery item not found'], 404);
        }

        // Delete the image file if it exists
        if ($gallery->image && Storage::exists('public/' . $gallery->image)) {
            Storage::delete('public/' . $gallery->image);
        }

        // Delete the gallery item from the database
        $gallery->delete();

        return response()->json(['message' => 'Green Energy Gallery deleted successfully'], 200);
    }
}
