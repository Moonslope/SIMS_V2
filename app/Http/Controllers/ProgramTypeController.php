<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProgramTypeRequest;
use App\Models\ProgramType;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;

class ProgramTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $programTypes = ProgramType::query()
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('program_name', 'LIKE', "%{$search}%")
                        ->orWhere('description', 'LIKE', "%{$search}%");
                });
            })
            ->orderBy('program_name', 'asc')
            ->paginate(10);

        return view('academics.program_types.index', compact('programTypes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('academics.program_types.add');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, ProgramTypeRequest $programTypeRequest)
    {
        $validated = $programTypeRequest->validated();
        $programType = ProgramType::create($validated);

        // Log activity
        ActivityLogService::created($programType, "Created program type: '{$programType->program_name}'");

        return redirect()->route('program-types.index')->with('success', 'New Program has been created.');
    }

    /**
     * Display the specified resource.
     */
    public function show(ProgramType $programType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProgramType $programType)
    {
        $programType = ProgramType::findOrFail($programType->id);
        return view('academics.program_types.edit', compact('programType'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProgramType $programType, ProgramTypeRequest $programTypeRequest)
    {
        $oldProgramName = $programType->program_name;

        $updated_data = $programTypeRequest->validated();
        $programType->update($updated_data);

        // Log activity
        if ($oldProgramName !== $programType->program_name) {
            ActivityLogService::updated($programType, "Updated program type from '{$oldProgramName}' to '{$programType->program_name}'");
        } else {
            ActivityLogService::updated($programType, "Updated program type: '{$programType->program_name}'");
        }

        return redirect()->route('program-types.index')->with('success', 'Program Details has been updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProgramType $programType)
    {
        $programType->delete();
        return redirect()->route('program-types.index')->with('success', 'The selected program has been deleted successfully.');
    }
}
