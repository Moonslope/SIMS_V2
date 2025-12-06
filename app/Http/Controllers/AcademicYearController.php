<?php

namespace App\Http\Controllers;

use App\Http\Requests\AcademicYearRequest;
use App\Models\AcademicYear;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;

class AcademicYearController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $academicYears = AcademicYear::query()
            ->when($search, function ($query, $search) {
                $query->where('year_name', 'LIKE', "%{$search}%")
                    ->orWhere('description', 'LIKE', "%{$search}%");
            })
            ->orderBy('start_date', 'desc')
            ->paginate(10);

        return view('system.academic_year.index', compact('academicYears'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $activeYear = AcademicYear::where('is_active', true)->first();
        return view('system.academic_year.add', compact('activeYear'));
    }

    /**
     * Store a newly created resource in storage. 
     */
    public function store(Request $request, AcademicYearRequest $academicYearRequest)
    {
        $validated = $academicYearRequest->validated();
        
        // If setting this year as active, deactivate all other years
        if (isset($validated['is_active']) && $validated['is_active']) {
            AcademicYear::where('is_active', true)->update(['is_active' => false]);
        }
        
        $academicYear = AcademicYear::create($validated);

        // Log activity
        ActivityLogService::created($academicYear, "Created new Academic Year: {$academicYear->year_name}");

        return redirect()->route('academic-years.index')
            ->with('success', 'New Academic Year has been created successfully.');
    }

    /**
     * Display the specified resource. 
     */
    public function show(AcademicYear $academicYear)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AcademicYear $academicYear)
    {
        $activeYear = AcademicYear::where('is_active', true)->where('id', '!=', $academicYear->id)->first();
        return view('system.academic_year.edit', compact('academicYear', 'activeYear'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AcademicYear $academicYear, AcademicYearRequest $academicYearRequest)
    {
        $oldYearName = $academicYear->year_name;
        $updated_data = $academicYearRequest->validated();
        
        // If setting this year as active, deactivate all other years
        if (isset($updated_data['is_active']) && $updated_data['is_active']) {
            AcademicYear::where('id', '!=', $academicYear->id)
                ->where('is_active', true)
                ->update(['is_active' => false]);
        }
        
        $academicYear->update($updated_data);

        // Log activity
        ActivityLogService::updated($academicYear, "Updated Academic Year from '{$oldYearName}' to '{$academicYear->year_name}'");

        return redirect()->route('academic-years.index')
            ->with('success', 'Academic Year Details has been updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AcademicYear $academicYear)
    {
        $yearName = $academicYear->year_name;

        // Log activity before deletion
        ActivityLogService::deleted($academicYear, "Deleted Academic Year: {$yearName}");

        $academicYear->delete();

        return redirect()->route('academic-years.index')
            ->with('success', 'The selected academic year has been deleted successfully.');
    }
}
