<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\ContactConfirmation;
use App\Mail\ContactReply;
use App\Mail\NewContactSubmission;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    /**
     * Display a listing of contact submissions (admin only).
     * GET /api/admin/contacts?page=1&status=new&category=sales_inquiry
     */
    public function index(Request $request)
    {
        $query = Contact::query();

        // Filter by status
        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }

        // Filter by category
        if ($request->has('category') && !empty($request->category)) {
            $query->where('category', $request->category);
        }

        // Order by newest first
        $query->orderBy('created_at', 'desc');

        // Pagination
        $perPage = $request->get('per_page', 15);
        $contacts = $query->paginate($perPage);

        return response()->json($contacts);
    }

    /**
     * Store a newly created contact submission (public).
     * POST /api/contacts
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'nullable|string|max:20',
            'category' => 'required|in:sales_inquiry,tech_support,general',
            'message' => 'required|string|min:10|max:2000',
        ]);

        $contact = Contact::create($validated);

        // Send auto-reply to customer
        try {
            Mail::to($contact->email)->send(new ContactConfirmation($contact));
        } catch (\Exception $e) {
            // Log email error but don't fail the request
            Log::error('Failed to send contact confirmation email: ' . $e->getMessage());
        }

        // Send notification to admin
        try {
            $adminEmail = config('mail.admin_email', 'admin@rtech.test');
            Mail::to($adminEmail)->send(new NewContactSubmission($contact));
        } catch (\Exception $e) {
            // Log email error but don't fail the request
            Log::error('Failed to send admin notification email: ' . $e->getMessage());
        }

        return response()->json($contact, Response::HTTP_CREATED);
    }

    /**
     * Display the specified contact submission (admin only).
     * GET /api/admin/contacts/{id}
     */
    public function show(Contact $contact)
    {
        // Mark as read when viewing
        if ($contact->status === 'new') {
            $contact->markAsRead();
        }

        return response()->json($contact);
    }

    /**
     * Update a contact submission with admin reply (admin only).
     * PUT /api/admin/contacts/{id}
     */
    public function update(Request $request, Contact $contact)
    {
        $validated = $request->validate([
            'status' => 'required|in:new,read,replied',
            'admin_reply' => 'nullable|string|min:10|max:5000',
        ]);

        $contact->update($validated);

        // Mark as replied if admin_reply is provided
        if (!empty($validated['admin_reply'])) {
            $contact->markAsReplied();
            
            // Send reply email to customer
            try {
                Mail::to($contact->email)->send(new ContactReply($contact));
            } catch (\Exception $e) {
                // Log email error but don't fail the request
                Log::error('Failed to send contact reply email: ' . $e->getMessage());
            }
        }

        return response()->json($contact);
    }

    /**
     * Remove the specified contact submission (admin only).
     * DELETE /api/admin/contacts/{id}
     */
    public function destroy(Contact $contact)
    {
        $contact->delete();

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
