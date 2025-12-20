<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

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

        // TODO: Send emails (auto-reply to customer + notification to admin)
        // Mail::send(...);

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
            // TODO: Send reply email to customer
            // Mail::send(...);
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
