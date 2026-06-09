<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LeaveType;
use App\Models\Holiday;
use App\Models\LeaveRequest;
use App\Models\LeaveBalance;
use App\Models\Employee;
use Carbon\Carbon;

class HolidayController extends Controller
{
     public function index(Request $request)
    {
        $year     = $request->integer('year', now()->year);
        $holidays = Holiday::forYear($year)->orderBy('date')->get();

        return view('hr.holiday_index', compact('holidays', 'year'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'         => 'required|string|max:100',
            'date'         => 'required|date',
            'date_to'      => 'nullable|date|after_or_equal:date',
            'type'         => 'required|in:Public Holiday,National Holiday,Religious Holiday,Hospital Holiday,Optional',
            'is_recurring' => 'boolean',
            'description'  => 'nullable|string',
        ]);

        $from = Carbon::parse($validated['date']);
        $to   = isset($validated['date_to'])
            ? Carbon::parse($validated['date_to'])
            : $from->copy();

        $validated['total_days']   = $from->diffInDays($to) + 1;
        $validated['year']         = $from->year;
        $validated['is_recurring'] = $request->boolean('is_recurring');
        $validated['is_active']    = true;

        Holiday::create($validated);

        return redirect()->route('hr.holidays.index', ['year' => $validated['year']])
            ->with('success', "Holiday \"{$validated['name']}\" added.");
    }

    public function update(Request $request, Holiday $holiday)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:100',
            'date'        => 'required|date',
            'date_to'     => 'nullable|date|after_or_equal:date',
            'type'        => 'required|in:Public Holiday,National Holiday,Religious Holiday,Hospital Holiday,Optional',
            'description' => 'nullable|string',
            'is_active'   => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        $holiday->update($validated);

        return back()->with('success', "Holiday \"{$holiday->name}\" updated.");
    }

    public function destroy(Holiday $holiday)
    {
        $holiday->delete();
        return back()->with('success', 'Holiday deleted.');
    }
}
