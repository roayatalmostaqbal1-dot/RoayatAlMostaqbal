<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\SuperAdmin\ContactResource;
use App\Mail\ContactReplyMail;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\{Mail,Log};

class ContactController extends Controller
{
    /**
     * Display a listing of all contact submissions.
     */
    public function index(Request $request): JsonResponse
    {

        $perPage = $request->query('per_page', 15);
        $status = $request->query('status');
        $search = $request->query('search');

        $query = Contact::query();

        // Filter by status
        if ($status) {
            $query->where('status', $status);
        }

        // Search by name, email, or company
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('company', 'like', "%{$search}%");
            });
        }

        $contacts = $query->orderBy('created_at', 'desc')->paginate($perPage);
        return response()->json([
            'data' => ContactResource::collection($contacts->items()),
            'pagination' => [
                'total' => $contacts->total(),
                'per_page' => $contacts->perPage(),
                'current_page' => $contacts->currentPage(),
                'last_page' => $contacts->lastPage(),
                'from' => $contacts->firstItem(),
                'to' => $contacts->lastItem(),
            ],
        ]);
    }

    /**
     * Display the specified contact.
     */
    public function show(Contact $contact): JsonResponse
    {
        // Mark as read if status is 'new'
        if ($contact->status === 'new') {
            $contact->update(['status' => 'read']);
        }

        return response()->json([
            'data' => new ContactResource($contact),
        ]);
    }

    /**
     * Update the specified contact (for replying).
     */
    public function update(Request $request, Contact $contact): JsonResponse
    {
        $validated = $request->validate([
            'status' => 'sometimes|in:new,read,replied',
            'reply_message' => 'sometimes|string|max:5000',
        ]);

        if (isset($validated['reply_message'])) {
            $validated['status'] = 'replied';
            $validated['replied_at'] = now();
        }

        $contact->update($validated);

        // Send reply email if a reply message was provided
        if (isset($validated['reply_message'])) {
            Mail::to($contact->email)->send(new ContactReplyMail($contact, $validated['reply_message']));
        }

        return response()->json([
            'data' => new ContactResource($contact),
            'message' => 'Contact updated successfully',
        ]);
    }

    /**
     * Remove the specified contact.
     */
    public function destroy(Contact $contact): JsonResponse
    {
        $contact->delete();

        return response()->json([
            'message' => 'Contact deleted successfully',
        ]);
    }
}
