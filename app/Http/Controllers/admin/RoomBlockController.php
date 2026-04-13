<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Guest;
use App\Models\Room;
use App\Models\RoomBlock;
use App\Models\RoomBlockRoom;
use App\Models\RoomType;
use App\Services\RoomAvailabilityService;
use App\Services\RoomBlockService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class RoomBlockController extends Controller
{
    public function __construct(
        private readonly RoomBlockService $roomBlocks,
        private readonly RoomAvailabilityService $availability
    ) {
    }

    public function index()
    {
        $blocks = RoomBlock::query()
            ->withCount(['roomBlockRooms'])
            ->orderByDesc('id')
            ->get();

        return view('admin.room_blocks.index', compact('blocks'));
    }

    public function create()
    {
        $types = RoomType::query()->orderBy('name')->get();
        $rooms = Room::query()->with(['roomType', 'floor'])->orderBy('room_number')->get();

        return view('admin.room_blocks.create', compact('types', 'rooms'));
    }

    /**
     * createBlock(): creates a block + assigns rooms.
     */
    public function createBlock(Request $request)
    {
        $data = $request->validate([
            'group_name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'status' => 'required|in:tentative,confirmed,cancelled',
            'release_at' => 'nullable|date',
            'notes' => 'nullable|string|max:5000',

            // assign specific rooms
            'room_ids' => 'nullable|array',
            'room_ids.*' => 'integer|exists:rooms,id',

            // or auto-assign by type
            'room_type_id' => 'nullable|integer|exists:room_types,id',
            'total_rooms' => 'nullable|integer|min:1',
        ]);

        $block = $this->roomBlocks->createBlock($data);

        return redirect()->route('admin.room-blocks.show', $block->id)
            ->with('success', 'Room block created successfully.');
    }

    public function show(int $id)
    {
        $block = RoomBlock::query()
            ->with([
                'roomBlockRooms.room.roomType',
                'roomBlockRooms.assignedGuest',
                'reservations' => function ($q) {
                    $q->with(['guest', 'rooms', 'reservationRooms'])
                        ->orderByDesc('id');
                },
            ])
            ->findOrFail($id);

        $availableRooms = collect();
        try {
            $availableRooms = Room::query()
                ->with('roomType')
                ->orderBy('room_number');
            $this->availability->constrainToAvailableRooms(
                $availableRooms,
                $block->start_date->toDateString(),
                $block->end_date->toDateString(),
                false,
                $block->id
            );
            $availableRooms = $availableRooms->get();
        } catch (\Throwable $e) {
            $availableRooms = collect();
        }

        return view('admin.room_blocks.show', compact('block', 'availableRooms'));
    }

    /**
     * assignRooms(): add more rooms into an existing block.
     */
    public function assignRooms(Request $request, int $id)
    {
        $block = RoomBlock::findOrFail($id);

        $data = $request->validate([
            'room_ids' => 'required|array|min:1',
            'room_ids.*' => 'integer|exists:rooms,id',
        ]);

        $this->roomBlocks->attachRoomsToBlock($block, $data['room_ids']);

        return redirect()->route('admin.room-blocks.show', $block->id)
            ->with('success', 'Rooms assigned to block successfully.');
    }

    public function unassignRooms(Request $request, int $id)
    {
        $block = RoomBlock::findOrFail($id);

        $data = $request->validate([
            'room_block_room_ids' => 'required|array|min:1',
            'room_block_room_ids.*' => 'integer|exists:room_block_rooms,id',
        ]);

        $this->roomBlocks->unassignRooms($block, $data['room_block_room_ids']);

        return redirect()->route('admin.room-blocks.show', $block->id)
            ->with('success', 'Rooms unassigned successfully.');
    }

    public function update(Request $request, int $id)
    {
        $block = RoomBlock::findOrFail($id);

        $data = $request->validate([
            'group_name' => 'required|string|max:255',
            'status' => 'required|in:tentative,confirmed,cancelled',
            'release_at' => 'nullable|date',
            'notes' => 'nullable|string|max:5000',
        ]);

        $block->fill($data);
        $block->save();

        return redirect()->route('admin.room-blocks.show', $block->id)
            ->with('success', 'Room block updated successfully.');
    }

    /**
     * releaseBlock(): releases inventory (manual or auto-expire).
     */
    public function releaseBlock(int $id)
    {
        $block = RoomBlock::findOrFail($id);
        $this->roomBlocks->releaseBlock($block);

        return redirect()->route('admin.room-blocks.show', $block->id)
            ->with('success', 'Room block released (inventory returned).');
    }

    public function convert(int $id)
    {
        $block = RoomBlock::query()
            ->with(['roomBlockRooms.room.roomType', 'roomBlockRooms.assignedGuest'])
            ->findOrFail($id);

        $guests = Guest::query()->orderBy('first_name')->orderBy('last_name')->get();

        return view('admin.room_blocks.convert', compact('block', 'guests'));
    }

    /**
     * convertToReservation(): converts selected block rooms into reservations.
     */
    public function convertToReservation(Request $request, int $id)
    {
        $block = RoomBlock::findOrFail($id);

        if (($block->status ?? null) !== 'confirmed') {
            return redirect()->route('admin.room-blocks.show', $block->id)
                ->withErrors(['status' => 'Confirm the room block before converting it to reservations.']);
        }

        if (!empty($block->released_at)) {
            return redirect()->route('admin.room-blocks.show', $block->id)
                ->withErrors(['status' => 'This room block was released and cannot be converted.']);
        }

        $data = $request->validate([
            'room_block_room_ids' => 'required|array|min:1',
            'room_block_room_ids.*' => 'integer|exists:room_block_rooms,id',

            // per blocked-room assignments
            'assignments' => 'nullable|array',
            'assignments.*.guest_id' => 'nullable|integer|exists:guests,id',
            'assignments.*.guest.first_name' => 'nullable|string|max:255',
            'assignments.*.guest.last_name' => 'nullable|string|max:255',
            'assignments.*.guest.email' => 'nullable|email|max:255',
            'assignments.*.guest.phone' => 'nullable|string|max:50',
            'assignments.*.rate_plan_named' => 'nullable|string|max:255',

            // bulk guest import (optional)
            'bulk_guest_csv' => 'nullable|string|max:20000',
        ]);

        $assignments = $data['assignments'] ?? [];

        if (!empty($data['bulk_guest_csv'])) {
            $assignments = $this->applyBulkGuestImport($block, $data['room_block_room_ids'], $assignments, $data['bulk_guest_csv']);
        }

        $reservationIds = $this->roomBlocks->convertToReservations($block, $data['room_block_room_ids'], $assignments);

        return redirect()->route('admin.room-blocks.show', $block->id)
            ->with('success', 'Converted to reservations: ' . implode(', ', $reservationIds));
    }

    private function applyBulkGuestImport(RoomBlock $block, array $roomBlockRoomIds, array $assignments, string $csv): array
    {
        $ids = collect($roomBlockRoomIds)->map(fn ($v) => (int) $v)->values();

        $rooms = RoomBlockRoom::query()
            ->where('room_block_id', $block->id)
            ->whereIn('id', $ids)
            ->orderBy('id')
            ->get();

        // Lines: first_name,last_name,email,phone
        $lines = collect(preg_split('/\r\n|\r|\n/', $csv))
            ->map(fn ($l) => trim((string) $l))
            ->filter();

        $parsedGuests = [];
        foreach ($lines as $line) {
            $cols = array_map('trim', str_getcsv($line));
            if (count($cols) < 3) {
                continue;
            }

            $parsedGuests[] = [
                'first_name' => $cols[0] ?? null,
                'last_name' => $cols[1] ?? null,
                'email' => $cols[2] ?? null,
                'phone' => $cols[3] ?? null,
            ];
        }

        if (empty($parsedGuests)) {
            return $assignments;
        }

        $cursor = 0;
        foreach ($rooms as $room) {
            if (!empty($assignments[$room->id]['guest_id']) || !empty($assignments[$room->id]['guest'])) {
                continue;
            }
            if (!isset($parsedGuests[$cursor])) {
                break;
            }
            $assignments[$room->id]['guest'] = $parsedGuests[$cursor];
            $cursor++;
        }

        return $assignments;
    }
}
