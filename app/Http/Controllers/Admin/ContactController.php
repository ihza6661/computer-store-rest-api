<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\ContactReply;

class ContactController extends Controller
{
    /**
     * Update contact status and send reply
     */
    public function update(Request $request, Contact $contact)
    {
        $validated = $request->validate([
            'status' => 'required|in:new,read,replied',
            'admin_reply' => 'nullable|string|min:10|max:5000',
        ]);

        $contact->update($validated);

        // If there's a reply, mark as replied and send email
        if (!empty($validated['admin_reply'])) {
            $contact->markAsReplied();
            
            try {
                Mail::to($contact->email)->send(new ContactReply($contact));
            } catch (\Exception $e) {
                // Log but don't fail the request
                Log::error('Failed to send contact reply email: ' . $e->getMessage());
            }
        }

        return redirect()->route('admin.contacts.show', $contact)
            ->with('success', 'Reply sent successfully!');
    }
}
