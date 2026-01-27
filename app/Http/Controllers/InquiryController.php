<?php

namespace App\Http\Controllers;

use App\Mail\NewInquiryMail;
use App\Models\Inquiry;
use App\Models\Setting;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class InquiryController extends Controller
{
    public function store(Request $request, Unit $unit)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'message' => 'nullable|string|max:1000',
        ]);

        $inquiry = $unit->inquiries()->create($validated);

        // Send email notification to admin
        $adminEmail = Setting::get('admin_email');
        if ($adminEmail) {
            try {
                Mail::to($adminEmail)->send(new NewInquiryMail($inquiry));
            } catch (\Exception $e) {
                \Log::error('Failed to send inquiry email: ' . $e->getMessage());
            }
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => __('Thank you! We will contact you soon.'),
            ]);
        }

        return back()->with('success', __('Thank you! We will contact you soon.'));
    }
}
