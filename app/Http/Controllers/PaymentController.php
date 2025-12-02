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
            'billingItem.feeStructure',
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
            $billing->update(['status' => 'paid']);
        } else {
            $billing->update(['status' => 'partial']);
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
        $billingItems = $validated['billing_items'];
        $paymentDetails = [];
        $totalAmount = 0;

        // Validate each selected billing item
        foreach ($billingItems as $itemId => $itemData) {
            if (isset($itemData['selected'])) {
                $billingItem = \App\Models\BillingItem::findOrFail($itemId);
                $amount = floatval($itemData['amount']);
                $remainingBalance = $billingItem->amount - $billingItem->amount_paid;

                if ($amount > $remainingBalance) {
                    return back()->withErrors([
                        'billing_items' => "Payment amount for {$billingItem->feeStructure->fee_name} cannot exceed remaining balance of ₱" . number_format($remainingBalance, 2)
                    ])->withInput();
                }

                $paymentDetails[] = [
                    'item' => $billingItem,
                    'amount' => $amount
                ];
                $totalAmount += $amount;
            }
        }

        if (empty($paymentDetails)) {
            return back()->withErrors([
                'billing_items' => 'Please select at least one fee to pay.'
            ])->withInput();
        }

        // Create payments for each billing item
        $referenceNumber = $validated['reference_number'];
        $createdPayments = [];

        foreach ($paymentDetails as $detail) {
            $billingItem = $detail['item'];
            $amount = $detail['amount'];

            // Create payment
            $payment = Payment::create([
                'billing_id' => $billing->id,
                'billing_item_id' => $billingItem->id,
                'academic_year_id' => $billing->enrollment->academic_year_id,
                'amount_paid' => $amount,
                'payment_date' => now(),
                'reference_number' => $referenceNumber,
                'purpose' => $billingItem->feeStructure->fee_name,
                'description' => $validated['description'] ?? null,
                'processedBy' => Auth::id()
            ]);

            // Update billing item
            $newAmountPaid = $billingItem->amount_paid + $amount;
            $newStatus = $newAmountPaid >= $billingItem->amount ? 'paid' : ($newAmountPaid > 0 ? 'partial' : 'unpaid');
            $billingItem->update([
                'amount_paid' => $newAmountPaid,
                'status' => $newStatus,
                'payment_date' => now(),
                'remarks' => $newStatus === 'paid' ? 'Paid' : 'Unpaid'
            ]);

            $createdPayments[] = $payment;
        }

        // Update billing status 
        $totalPaid = $billing->billingItems->sum('amount_paid');
        if ($totalPaid >= $billing->total_amount) {
            $billing->update(['status' => 'paid']);
        } else {
            $billing->update(['status' => 'partial']);
        }

        // Log activity
        $studentName = $billing->enrollment->student->first_name . ' ' . $billing->enrollment->student->last_name;
        $feeNames = collect($paymentDetails)->pluck('item.feeStructure.fee_name')->join(', ');
        ActivityLogService::created($createdPayments[0], "Processed payment of ₱{$totalAmount} for {$studentName} - Fees: {$feeNames} (OR: {$referenceNumber})");

        return redirect()->route('enrollments.show', $billing->enrollment_id)
            ->with('success', 'Payment processed successfully! ' . count($paymentDetails) . ' fee(s) paid. OR Number: ' . $referenceNumber);
    }

    public function generateReport(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $academicYearId = $request->input('academic_year');

        $academicYear = null;
        if ($academicYearId && $academicYearId !== 'all') {
            $academicYear = \App\Models\AcademicYear::find($academicYearId);
        }

        $query = Payment::with([
            'billing.enrollment.student',
            'billing.enrollment.academicYear',
            'academicYear'
        ])->orderBy('payment_date', 'desc');

        // Filter by date range
        if ($startDate && $endDate) {
            $query->whereBetween('payment_date', [$startDate, $endDate]);
        } elseif ($academicYear) {
            // Filter by academic year if no date range provided
            $query->whereHas('billing.enrollment', function ($q) use ($academicYear) {
                $q->where('academic_year_id', $academicYear->id);
            });
        }

        $payments = $query->get();

        $totalPayments = $payments->count();
        $totalCollected = $payments->sum('amount_paid');
        $averagePayment = $totalPayments > 0 ? $totalCollected / $totalPayments : 0;

        return view('reports.payment-collection-report', compact(
            'payments',
            'totalPayments',
            'totalCollected',
            'averagePayment',
            'startDate',
            'endDate',
            'academicYear'
        ));
    }
}
