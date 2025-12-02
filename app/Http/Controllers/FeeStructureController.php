<?php

namespace App\Http\Controllers;

use App\Http\Requests\FeeStructureRequest;
use App\Models\AcademicYear;
use App\Models\FeeStructure;
use App\Models\GradeLevel;
use App\Models\ProgramType;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;

class FeeStructureController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $gradeLevelFilter = $request->input('grade_level');
        $programTypeFilter = $request->input('program_type');

        $gradeLevels = GradeLevel::where('is_active', true)->get();
        $programTypes = ProgramType::where('is_active', true)->get();

        $feeStructures = FeeStructure::with(['gradeLevel', 'programType'])
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    // Search fee name
                    $q->where('fee_name', 'LIKE', "%{$search}%")
                        // Search amount
                        ->orWhere('amount', 'LIKE', "%{$search}%")
                        // Search in grade level relationship
                        ->orWhereHas('gradeLevel', function ($q) use ($search) {
                            $q->where('grade_name', 'LIKE', "%{$search}%");
                        })
                        // Search in program type relationship
                        ->orWhereHas('programType', function ($q) use ($search) {
                            $q->where('program_name', 'LIKE', "%{$search}%");
                        });
                });
            })
            ->when($gradeLevelFilter && $gradeLevelFilter !== 'all', function ($query) use ($gradeLevelFilter) {
                $query->where('grade_level_id', $gradeLevelFilter);
            })
            ->when($programTypeFilter && $programTypeFilter !== 'all', function ($query) use ($programTypeFilter) {
                $query->where('program_type_id', $programTypeFilter);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('financials.fee_structure.index', compact('feeStructures', 'gradeLevels', 'programTypes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $gradeLevels = GradeLevel::where('is_active', true)->get();
        $programTypes = ProgramType::where('is_active', true)->get();
        return view('financials.fee_structure.create', compact('gradeLevels', 'programTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, FeeStructureRequest $feeStructureRequest)
    {
        $validated = $feeStructureRequest->validated();

        $createdCount = 0;

        // Loop through each fee and create it
        foreach ($validated['fees'] as $feeData) {
            $feeStructure = FeeStructure::create([
                'grade_level_id' => $validated['grade_level_id'],
                'program_type_id' => $validated['program_type_id'],
                'fee_name' => $feeData['fee_name'],
                'amount' => $feeData['amount'],
                'is_active' => $feeData['is_active'],
            ]);

            // Log activity
            ActivityLogService::created($feeStructure, "Created fee structure: '{$feeStructure->fee_name}'");
            $createdCount++;
        }

        $message = $createdCount > 1
            ? "Successfully created {$createdCount} fee structures."
            : "Successfully created fee structure.";

        return redirect()->route('fee-structures.index')->with('success', $message);
    }

    /**
     * Display the specified resource.
     */
    public function show(FeeStructure $feeStructure)
    {
        return view('financials.fee_structure.show', compact('feeStructure'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FeeStructure $feeStructure)
    {
        $gradeLevels = GradeLevel::where('is_active', true)->get();
        $programTypes = ProgramType::where('is_active', true)->get();

        return view('financials.fee_structure.edit', compact(
            'feeStructure',
            'gradeLevels',
            'programTypes'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, FeeStructure $feeStructure)
    {
        $oldFeeName = $feeStructure->fee_name;

        $validated = $request->validate([
            'fee_name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'grade_level_id' => 'nullable|exists:grade_levels,id',
            'program_type_id' => 'required|exists:program_types,id',
            'is_active' => 'boolean'
        ]);

        $feeStructure->update($validated);

        // Log activity
        if ($oldFeeName !== $feeStructure->fee_name) {
            ActivityLogService::updated($feeStructure, "Updated fee structure from '{$oldFeeName}' to '{$feeStructure->fee_name}'");
        } else {
            ActivityLogService::updated($feeStructure, "Updated fee structure: '{$feeStructure->fee_name}'");
        }

        return redirect()->route('fee-structures.index')
            ->with('success', 'Fee structure updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FeeStructure $feeStructure)
    {
        $feeName = $feeStructure->fee_name;

        // Log activity before deletion
        ActivityLogService::deleted($feeStructure, "Deleted fee structure: '{$feeName}'");

        $feeStructure->delete();

        return redirect()->route('fee-structures.index')
            ->with('success', 'Fee structure deleted successfully!');
    }
}
