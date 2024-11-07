<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log; 
use Illuminate\Http\Request;
use App\Models\Payment;
use Auth;

class PaymentController extends Controller
{
    public function success(Request $request)
{
    \Log::info('eSewa Payment Response:', $request->all());

    // Validation logic
    $validated = $request->validate([
        'amt' => 'required',
        'oid' => 'required',
        'refId' => 'required',
        'sub_heading' => 'nullable|string', // Validate sub_heading if present
    ]);

    // Save the payment
    $payment = new Payment();
    $payment->user_id = Auth::id();
    $payment->product_id = $request->oid;
    $payment->amount = $request->amt;
    $payment->transaction_id = $request->refId;
    $payment->status = 'completed';
    $payment->sub_heading = $request->sub_heading; // Save sub_heading
    $payment->save();

    // Redirect to student dashboard
    return redirect()->route('student.dashboard')->with('success', 'Payment successful!');
}

    
    public function failure(Request $request)
    {
        \Log::info('Payment Failure Response:', $request->all());
    
        // Redirect to student dashboard with error message
        return redirect()->route('student.dashboard')->with('error', 'Payment failed. Please try again.');
    }

    public function index()
{
    $payments = Payment::all(); // Fetch all payments from the database
    return view('admin.index', compact('payments')); // Return the correct view
}


    public function toggleStatus($id)
    {
        $payment = Payment::findOrFail($id);
        $payment->is_active = !$payment->is_active;
        $payment->save();
    
        return redirect()->route('payment.index')->with('success', 'Payment status updated successfully.');
    }
    


}
