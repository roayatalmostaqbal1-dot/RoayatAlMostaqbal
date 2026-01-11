<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Get paginated notifications for the authenticated user
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = $user->notifications();

        if ($request->has('read')) {
            if ($request->read === 'true') {
                $query = $user->readNotifications();
            } else {
                $query = $user->unreadNotifications();
            }
        }

        $notifications = $query->paginate($request->get('per_page', 10));

        return response()->json([
            'success' => true,
            'data' => $notifications->items(),
            'meta' => [
                'current_page' => $notifications->currentPage(),
                'last_page' => $notifications->lastPage(),
                'total' => $notifications->total(),
                'unread_count' => $user->unreadNotifications()->count(),
            ]
        ]);
    }

    /**
     * Mark a specific notification as read
     */
    public function markAsRead(string $id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        return response()->json([
            'success' => true,
            'unread_count' => Auth::user()->unreadNotifications()->count(),
        ]);
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();

        return response()->json([
            'success' => true,
            'unread_count' => 0,
        ]);
    }

    /**
     * Delete a notification
     */
    public function destroy(string $id)
    {
        Auth::user()->notifications()->findOrFail($id)->delete();

        return response()->json([
            'success' => true,
            'unread_count' => Auth::user()->unreadNotifications()->count(),
        ]);
    }
}
