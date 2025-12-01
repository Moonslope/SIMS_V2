<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use App\Models\Payment;
use App\Models\Billing;
use App\Models\Enrollment;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\PaymentRequest;
use App\Http\Requests\BillingPaymentRequest;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $academicYearFilter = $request->input('academic_year');

        $academicYears = \App\Models\AcademicYear::orderBy('year_name', 'desc')->get();

        $payments = Payment::with([
            'billing.enrollment.student',
            'billing.enrollment.gradeLevel',
            'academicYear',
            'processedByUser'
        ])
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    // Search amount paid
                    $q->where('amount_paid', 'LIKE', "%{$search}%")
                        // Search reference number
                        ->orWhere('reference_number', 'LIKE', "%{$search}%")
                        // Search description
                        ->orWhere('description', 'LIKE', "%{$search}%")
                        // Search payment date
                        ->orWhere('payment_date', 'LIKE', "%{$search}%")
                        // Search in student through billing
                        ->orWhereHas('billing.enrollment.student', function ($q) use ($search) {
                            $q->where('first_name', 'LIKE', "%{$search}%")
                                ->orWhere('middle_name', 'LIKE', "%{$search}%")
                                ->orWhere('last_name', 'LIKE', "%{$search}%")
                                ->orWhere('learner_reference_number', 'LIKE', "%{$search}%")
                                ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ? ", ["%{$search}%"])
                                ->orWhereRaw("CONCAT(first_name, ' ', middle_name, ' ', last_name) LIKE ?", ["%{$search}%"]);
                        })
                        // Search in academic year
                        ->orWhereHas('academicYear', function ($q) use ($search) {
                            $q->where('year_name', 'LIKE', "%{$search}%");
                        })
                        // Search in processed by user
                        ->orWhereHas('processedByUser', function ($q) use ($search) {
                            $q->where('first_name', 'LIKE', "%{$search}%")
                                ->orWhere('last_name', 'LIKE', "%{$search}%")
                                ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$search}%"]);
                        });
                });
            })
            ->when($academicYearFilter && $academicYearFilter !== 'all', function ($query) use ($academicYearFilter) {
                $query->where('academic_year_id', $academicYearFilter);
            })
            ->orderBy('payment_date', 'desc')
            ->paginate(10);

        return view('financials.payment.index', compact('payments', 'academicYears'));
    }
    public function store(PaymentRequest $request)
    {
        $validated = $request->validated();
        $billing = Billing::findOrFail($validated['billing_id']);

        // Validate amount doesn't exceed remaining balance
        $totalPaid = $billing->payments()->sum('amount_paid');
        $remainingBalance = $billing->total_amount - $totalPaid;

        if ($validated['amount_paid'] > $remainingBalance) {
            return back()->withErrors([
                'amount_paid' => 'Payment amount cannot exceed remaining balance of ₱' . number_format($remainingBalance, 2)
            ]);
        }

        // Generate unique reference number
        do {
            $referenceNumber = 'PAY-' . date('Ymd') . '-' . strtoupper(Str::random(6));
        } while (Payment::where('reference_number', $referenceNumber)->exists());

        // Create payment
        $payment = Payment::create([
            'billing_id' => $validated['billing_id'],
            'academic_year_id' => $validated['academic_year_id'],
            'amount_paid' => $validated['amount_paid'],
            'payment_date' => now(),
            'reference_number' => $referenceNumber,
            'description' => $validated['description'] ?? null,
            'processedBy' => Auth::id()
        ]);

        // Update billing status
        $newTotalPaid = $billing->payments()->sum('amount_paid');
        if ($newTotalPaid >= $billing->total_amount) {
            $billing->update(['status' => 'Paid']);
        } else {
            $billing->update(['status' => 'Partial']);
        }

        // Log activity
        $studentName = $billing->enrollment->student->first_name . ' ' . $billing->enrollment->student->last_name;
        ActivityLogService::created($payment, "Recorded payment of ₱{$validated['amount_paid']} for student: '{$studentName}' (Ref: {$referenceNumber})");

        return redirect()->route('enrollments.billing', ['enrollment' => $billing->enrollment_id])
            ->with('success', 'Payment recorded successfully! Reference Number: ' . $referenceNumber);
    }

    /**
     * Process payment for existing bill
     */
    public function processBillingPayment(BillingPaymentRequest $request, Billing $billing)
    {
        $validated = $request->validated();

        // Generate unique reference number
        do {
            $referenceNumber = 'PAY-' . date('Ymd') . '-' . strtoupper(Str::random(6));
        } while (Payment::where('reference_number', $referenceNumber)->exists());

        // Create payment 
        $payment = Payment::create([
            'billing_id' => $billing->id,
            'academic_year_id' => $billing->enrollment->academic_year_id,
            'amount_paid' => $validated['amount_paid'],
            'payment_date' => now(),
            'reference_number' => $referenceNumber,
            'description' => $validated['description'] ?? null,
            'processedBy' => Auth::id()
        ]);

        // Update billing status 
        $totalPaid = $billing->payments()->sum('amount_paid');
        if ($totalPaid >= $billing->total_amount) {
            $billing->update(['status' => 'Paid']);
        } else {
            $billing->update(['status' => 'Partial']);
        }

        // Log activity
        $studentName = $billing->enrollment->student->first_name . ' ' . $billing->enrollment->student->last_name;
        ActivityLogService::created($payment, "Processed billing payment of ₱{$validated['amount_paid']} for student: '{$studentName}' (Ref: {$referenceNumber})");

        return redirect()->route('billings.edit', $billing->id)
            ->with('success', 'Payment processed successfully! Reference: ' . $referenceNumber);
    }
}
