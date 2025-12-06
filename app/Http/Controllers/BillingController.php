<?php

namespace App\Http\Controllers;

use App\Models\Billing;
use Illuminate\Http\Request;

class BillingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
 public function index(Request $request)
    {
        $search = $request->input('search');

        $billings = Billing::with(['enrollment.student', 'enrollment.academicYear', 'enrollment.gradeLevel', 'enrollment.programType'])
            ->when($search, function ($query, $search) {
                $searchLower = strtolower("%{$search}%");
                $query->where(function ($q) use ($search, $searchLower) {
                    $q->whereRaw('LOWER(total_amount) LIKE ?', [$searchLower])
                        ->orWhereRaw('LOWER(status) LIKE ?', [$searchLower])
                        ->orWhereHas('enrollment.student', function ($q) use ($searchLower) {
                            $q->whereRaw('LOWER(first_name) LIKE ?', [$searchLower])
                                ->orWhereRaw('LOWER(middle_name) LIKE ?', [$searchLower])
                                ->orWhereRaw('LOWER(last_name) LIKE ?', [$searchLower])
                                ->orWhereRaw('LOWER(learner_reference_number) LIKE ?', [$searchLower])
                                ->orWhereRaw("LOWER(CONCAT(first_name, ' ', last_name)) LIKE ?", [$searchLower])
                                ->orWhereRaw("LOWER(CONCAT(first_name, ' ', middle_name, ' ', last_name)) LIKE ?", [$searchLower]);
                        })
                        ->orWhereHas('enrollment.academicYear', function ($q) use ($searchLower) {
                            $q->whereRaw('LOWER(year_name) LIKE ?', [$searchLower]);
                        })
                        ->orWhereHas('enrollment.programType', function ($q) use ($searchLower) {
                            $q->whereRaw('LOWER(program_name) LIKE ?', [$searchLower]);
                        })
                        ->orWhereHas('enrollment.gradeLevel', function ($q) use ($searchLower) {
                            $q->whereRaw('LOWER(grade_name) LIKE ?', [$searchLower]);
                        });
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('financials.Billing.index', compact('billings'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Billing $billing)
    {
        return view('financials.Billing.show', compact('billing'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Billing $billing)
    {
        $billing->load(['billingItems.feeStructure', 'enrollment.student', 'enrollment.academicYear', 'enrollment.gradeLevel', 'enrollment.programType']);
        return view('financials.Billing.edit', compact('billing'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Billing $billing)
    {
        // For billing updates (not payments)
        // Handle other billing updates here if needed

        return redirect()->route('billings.edit', $billing->id)
            ->with('success', 'Billing updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Billing $billing)
    {
        //
    }
}
