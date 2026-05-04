<?php

namespace App\Http\Controllers\OperationTheater;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OtRoom;
use App\Models\OtSchedule;
use App\Models\OtTeam;
use App\Models\Employee;
use Carbon\Carbon;

class OtRoomController extends Controller
{
    public function index()
    {
        $rooms = OtRoom::withCount([
            'schedules as today_schedules' => function ($q) {
                $q->whereDate('scheduled_date', today());
            }
        ])->orderBy('room_code')->paginate(20);

        $stats = [
            'total' => OtRoom::count(),
            'available' => OtRoom::where('status', 'Available')->count(),
            'occupied' => OtRoom::where('status', 'Occupied')->count(),
            'maintenance' => OtRoom::whereIn('status', ['Maintenance', 'Out of Service'])->count(),
        ];

        return view('operationtheater.otroom_index', compact('rooms', 'stats'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'room_code' => 'required|unique:ot_rooms,room_code|max:20',
            'name' => 'required|max:200',
            'room_type' => 'required|in:General,Cardiac,Neurology,Orthopedic,Gynecology,ENT,Eye,Trauma,Emergency',
            'status' => 'required|in:Available,Occupied,Cleaning,Maintenance,Out of Service',
            'floor' => 'nullable|max:50',
            'block' => 'nullable|max:50',
            'has_anesthesia_machine' => 'boolean',
            'has_ventilator' => 'boolean',
            'has_laparoscopy' => 'boolean',
            'has_c_arm' => 'boolean',
            'is_laminar_flow' => 'boolean',
            'equipment_notes' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        OtRoom::create(array_merge($validated, [
            'has_anesthesia_machine' => $request->boolean('has_anesthesia_machine'),
            'has_ventilator' => $request->boolean('has_ventilator'),
            'has_laparoscopy' => $request->boolean('has_laparoscopy'),
            'has_c_arm' => $request->boolean('has_c_arm'),
            'is_laminar_flow' => $request->boolean('is_laminar_flow'),
        ]));

        return back()->with('success', 'OT Room added successfully.');
    }

    public function update(Request $request, OtRoom $room)
    {
        $validated = $request->validate([
            'room_code' => ['required', 'max:20', \Illuminate\Validation\Rule::unique('ot_rooms', 'room_code')->ignore($room->id)],
            'name' => 'required|max:200',
            'room_type' => 'required|in:General,Cardiac,Neurology,Orthopedic,Gynecology,ENT,Eye,Trauma,Emergency',
            'status' => 'required|in:Available,Occupied,Cleaning,Maintenance,Out of Service',
            'floor' => 'nullable|max:50',
            'block' => 'nullable|max:50',
            'has_anesthesia_machine' => 'boolean',
            'has_ventilator' => 'boolean',
            'has_laparoscopy' => 'boolean',
            'has_c_arm' => 'boolean',
            'is_laminar_flow' => 'boolean',
            'equipment_notes' => 'nullable|string',
            'notes' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $room->update(array_merge($validated, [
            'has_anesthesia_machine' => $request->boolean('has_anesthesia_machine'),
            'has_ventilator' => $request->boolean('has_ventilator'),
            'has_laparoscopy' => $request->boolean('has_laparoscopy'),
            'has_c_arm' => $request->boolean('has_c_arm'),
            'is_laminar_flow' => $request->boolean('is_laminar_flow'),
            'is_active' => $request->boolean('is_active', true),
        ]));

        return back()->with('success', 'OT Room updated successfully.');
    }

    public function destroy(OtRoom $room)
    {
        if ($room->activeSchedules()->exists()) {
            return back()->with('error', 'Cannot delete: Room has active surgeries scheduled.');
        }
        $room->delete();
        return back()->with('success', 'OT Room removed.');
    }
}
