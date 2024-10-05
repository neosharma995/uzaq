<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\GalleryNew;

class GlryNewG extends Controller
{
    // Fetch all records (index)
    public function index()
    {
        $galleries = GalleryNew::all();
        return response()->json($galleries);
    }

    // Store a new image and heading (create)
    public function store(Request $request)
    {
        $request->validate([
            'imgHeading' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('GalleryNew_images', 'public');
        }

        $GalleryNew = new GalleryNew();
        $GalleryNew->imgHeading = $request->imgHeading;
        $GalleryNew->image = $imagePath; // Store path to the image
        $GalleryNew->save();

        return response()->json(['message' => 'GalleryNew created successfully', 'data' => $GalleryNew], 201);
    }

    // Fetch a specific record (show)
    public function show($id)
    {
        $GalleryNew = GalleryNew::find($id);

        if (!$GalleryNew) {
            return response()->json(['message' => 'GalleryNew not found'], 404);
        }

        return response()->json($GalleryNew);
    }

    // Update an existing record (update)
    public function update(Request $request, $id)
    {
        $GalleryNew = GalleryNew::find($id);

        if (!$GalleryNew) {
            return response()->json(['message' => 'GalleryNew not found'], 404);
        }

        $request->validate([
            'imgHeading' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        if ($request->hasFile('image')) {
            // Delete the old image
            if ($GalleryNew->image) {
                Storage::disk('public')->delete($GalleryNew->image);
            }
            // Store the new image
            $imagePath = $request->file('image')->store('GalleryNew_images', 'public');
            $GalleryNew->image = $imagePath;
        }

        $GalleryNew->imgHeading = $request->imgHeading;
        $GalleryNew->save();

        return response()->json(['message' => 'GalleryNew updated successfully', 'data' => $GalleryNew]);
    }

    // Delete a specific record (destroy)
    public function destroy($id)
    {
        $GalleryNew = GalleryNew::find($id);

        if (!$GalleryNew) {
            return response()->json(['message' => 'GalleryNew not found'], 404);
        }

        // Delete the image from storage
        if ($GalleryNew->image) {
            Storage::disk('public')->delete($GalleryNew->image);
        }

        $GalleryNew->delete();

        return response()->json(['message' => 'GalleryNew deleted successfully']);
    }
}
