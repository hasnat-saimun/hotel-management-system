<?php

namespace App\Http\Controllers\admin;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Room;
use App\Models\RoomType;
use App\Models\Floor;
use App\Models\Amenity;

class RoomController extends Controller
{
    public function index(Request $request)
    {
        $query = Room::with(['roomType', 'floor', 'amenities'])->orderBy('room_number');

        if ($request->filled('q')) {
            $q = $request->input('q');
            $query->where(function($w) use ($q) {
                $w->where('room_number', 'like', "%{$q}%")
                  ->orWhereHas('roomType', function($r) use ($q) {
                      $r->where('name', 'like', "%{$q}%");
                  })
                  ->orWhereHas('floor', function($f) use ($q) {
                      $f->where('name', 'like', "%{$q}%");
                  })
                  ->orWhere('notes', 'like', "%{$q}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $rooms = $query->paginate(25);
        return view('admin.rooms.index', compact('rooms'));
    }

    public function create()
    {
        $types = RoomType::where('is_active', true)->orderBy('name')->get();
        $floors = Floor::orderBy('level_number')->get();
        $amenities = Amenity::where('is_active', true)->orderBy('name')->get();
        return view('admin.rooms.create', compact('types', 'floors', 'amenities'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'room_number' => 'required|string|max:50|unique:rooms,room_number',
            'room_type_id' => 'required|integer|exists:room_types,id',
            'floor_id' => 'required|integer|exists:floors,id',
            'status' => 'required|in:available,occupied,reserved,dirty,clean,maintenance,out_of_service',
            'notes' => 'nullable|string',
            'is_active' => 'nullable|boolean',
            'amenities' => 'nullable|array',
            'amenities.*' => 'integer|exists:amenities,id',
            'images' => 'nullable|array'
        ]);

            $images = array();
            if ($request->hasFile('images')) {
                $files = $request->file('images');
                foreach ($files as $file) {
                    $image_name = md5(rand(1000, 10000));
                    $ext = strtolower($file->getClientOriginalExtension());
                    $image_full_name = $image_name . '.' . $ext;
                    $file_path = $file->store('images/rooms', 'public');
                    $images[] = $file_path;
                    
                }
            }
        $data['avatar'] = json_encode($images);
        $data['is_active'] = isset($data['is_active']) ? (bool)$data['is_active'] : true;
        $room = Room::create($data);

        $room->amenities()->sync($data['amenities'] ?? []);

        return redirect()->route('admin.rooms.index')->with('success', 'Room created');
    }

    public function edit($id)
    {
        $room = Room::with('amenities')->findOrFail($id);
        $types = RoomType::where('is_active', true)->orderBy('name')->get();
        $floors = Floor::orderBy('level_number')->get();
        $amenities = Amenity::where('is_active', true)->orderBy('name')->get();
        return view('admin.rooms.edit', compact('room','types','floors','amenities'));
    }

    public function update(Request $request, $id)
    {
        $room = Room::findOrFail($id);

        $data = $request->validate([
            'room_number' => 'required|string|max:50|unique:rooms,room_number,'.$room->id,
            'room_type_id' => 'required|integer|exists:room_types,id',
            'floor_id' => 'required|integer|exists:floors,id',
            'status' => 'required|in:available,occupied,reserved,dirty,clean,maintenance,out_of_service',
            'notes' => 'nullable|string',
            'is_active' => 'nullable|boolean',
            'amenities' => 'nullable|array',
            'amenities.*' => 'integer|exists:amenities,id',
        ]);

        $data['is_active'] = isset($data['is_active']) ? (bool)$data['is_active'] : false;
        $room->update($data);
        $room->amenities()->sync($data['amenities'] ?? []);

        return redirect()->route('admin.rooms.index')->with('success', 'Room updated');
    }

    public function deleteSingleRoomImage(Request $request, $id,$index)
    {

        $room = Room::findOrFail($id);

        $images = json_decode($room->avatar, true) ?? [];
        if (!isset($images[$index])) {
            return redirect()->route('admin.rooms.edit', $room->id)->with('error', 'Image not found');
        }
        $imageToDelete = $images[$index];


        // Remove image from array
        $images = array_values(array_diff($images, [$imageToDelete]));

        // Update database
        $room->avatar = json_encode($images);
        $room->save();
        // Remove from storage
    if (Storage::disk('public')->exists($imageToDelete)) {

        Storage::disk('public')->delete($imageToDelete);
    }

            return redirect()->route('admin.rooms.edit', $room->id)->with('success', 'Image removed');
        }

    public function updateRoomImage(Request $request, $id)
    {
        $room = Room::findOrFail($id);

        $data = $request->validate([
            'images' => 'nullable|array',
        ]);
            $existing = json_decode($room->avatar, true) ?? [];
            $new = [];
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $file ) {
                    $new[] = $file->store('images/rooms', 'public');  
                }
            }
            $merged = array_values(array_unique(array_merge($existing, $new)));
        $room->avatar = json_encode($merged);
        $room->save();
        return redirect()->route('admin.rooms.edit', $room->id)->with('success', 'Images updated');
    }

    public function bulkDestroy(Request $request)
    {
        $data = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:rooms,id'
        ]);

        $rooms = Room::whereIn('id', $data['ids'])->get();
        foreach ($rooms as $r) { $r->amenities()->detach(); }
        $count = Room::whereIn('id', $data['ids'])->delete();
        return redirect()->route('admin.rooms.index')->with('success', "$count rooms deleted");
    }

    public function destroy($id)
    {
        $room = Room::findOrFail($id);
        $room->amenities()->detach();
        $room->delete();
        return redirect()->route('admin.rooms.index')->with('success', 'Room deleted');
    }
}
