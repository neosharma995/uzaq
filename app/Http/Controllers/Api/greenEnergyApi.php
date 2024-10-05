<?php


namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\GreenEnergy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class greenEnergyApi extends Controller
{


    protected $baseUrl;    
    protected $imgUrl;    

    public function __construct()
    {
        
         $this->baseUrl = config('app.api_url');
         $this->imgUrl = config('app.img_url');
    }


    // Get all records
    public function index()
    {
        // Fetch only the required fields from the GreenEnergy model
        $greenEnergies = GreenEnergy::all(['id', 'name', 'image', 'short_description', 'long_description']);
    
        // Map over the data to modify the image URL if needed
        $greenEnergies = $greenEnergies->map(function ($greenEnergy) {
            // Check if image exists, if yes, prepend the base URL, otherwise set to null
            $greenEnergy->image = $greenEnergy->image ? $this->imgUrl . '/' . $greenEnergy->image : null;
            return $greenEnergy;
        });
    
        // Return the response with a message and the data
        return response()->json([
            'message' => 'Green Energy records retrieved successfully',
            'data'    => $greenEnergies
        ], 200);
    }
    
    // Store new record
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:204800',
            'short_description' => 'required|string',
            'long_description' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        // Handle image upload if provided
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('green_energies', 'public');
        }

        $greenEnergy = GreenEnergy::create([
            'name' => $request->name,
            'image' => $imagePath,
            'short_description' => $request->short_description,
            'long_description' => $request->long_description,
        ]);

        return response()->json($greenEnergy, 201);
    }

    // Show single record
    public function show($id)
    {
        $greenEnergy = GreenEnergy::find($id);

        if (!$greenEnergy) {
            return response()->json(['message' => 'Resource not found'], 404);
        }

        return response()->json($greenEnergy);
    }

    // Update record
    public function update(Request $request, $id)
    {
        $greenEnergy = GreenEnergy::find($id);

        if (!$greenEnergy) {
            return response()->json(['message' => 'Resource not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:204800',
            'short_description' => 'nullable|string',
            'long_description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        // Handle image upload if provided
        if ($request->hasFile('image')) {
            // Delete old image
            if ($greenEnergy->image) {
                Storage::disk('public')->delete($greenEnergy->image);
            }
            $imagePath = $request->file('image')->store('green_energies', 'public');
            $greenEnergy->image = $imagePath;
        }

        $greenEnergy->update($request->only(['name', 'short_description', 'long_description']));

        return response()->json($greenEnergy);
    }

    // Delete record
    public function destroy($id)
    {
        $greenEnergy = GreenEnergy::find($id);

        if (!$greenEnergy) {
            return response()->json(['message' => 'Resource not found'], 404);
        }

        if ($greenEnergy->image) {
            Storage::disk('public')->delete($greenEnergy->image);
        }

        $greenEnergy->delete();

        return response()->json(['message' => 'Resource deleted successfully']);
    }
}
