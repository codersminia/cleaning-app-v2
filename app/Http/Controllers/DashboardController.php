<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Prospect;
use App\Models\Facility;
use App\Models\Frequency;
use App\Models\Weekday;

class DashboardController extends Controller
{
    public function index()
    {
        // This loads the Blade view which mounts your Vue dashboard
        return view('dashboard');
    }

    public function prospects(){
        return view('prospects');
    }

    public function getProspects()
    {
        $prospects = Prospect::select('id', 'company_name')->orderBy('id', 'desc')->get();
        return response()->json($prospects);
    }

    public function saveprospect(Request $request)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'website' => 'nullable|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'area_in_sqft' => 'nullable|integer',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'ext' => 'nullable|string|max:10',
            'mobile' => 'nullable|string|max:50',
            'address' => 'required|string',
            'unit_number' => 'nullable|string|max:50',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'zip' => 'required|string|max:20',
        ]);

        $prospect = Prospect::create($validated);

         return response()->json([
            'message' => 'Prospect saved successfully!',
            'id' => $prospect->id
        ], 201);
    }

    public function showLocation($id)
    {
        $prospect = Prospect::findOrFail($id);
        return view('location', ['prospect' => $prospect]);
    }

    public function getfacilities()
    {
        $facilities = Facility::select('id', 'name')->orderBy('name')->get();
        return response()->json($facilities);
    }

    public function getfrequencies()
    {
        return response()->json(Frequency::select('id', 'label')->get());
    }

    public function getweekdays()
    {
        $weekdays = Weekday::orderBy('order')->get(['id', 'name', 'short_name', 'order']);
        return response()->json($weekdays);
    }
}
