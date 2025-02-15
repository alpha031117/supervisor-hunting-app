<?php

namespace App\Http\Controllers\ManageTitle;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{

    /**
     * Display all notifications for the authenticated user.
     */
    public function SendNotification()
    {
        // Fetch all notifications (both read and unread)
        $notifications = Notification::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        // Mark notifications as read
        foreach ($notifications as $notification) {
            // Check if the notification is unread
            if ($notification->status === 'Unread') {
                $notification->status = 'Read';
                $notification->save();
            }
        }

        // Fetch read notifications
        $readNotifications = $this->DisplayReadNotification();

        // Fetch unread notifications
        $unreadNotifications = $this->DisplayUnreadNotification();

        return view('ApplyTitle.Notification', compact('notifications', 'readNotifications', 'unreadNotifications'));
    }

    /**
     * Display all read notifications for the authenticated user.
     */
    private function DisplayReadNotification()
    {
        // Fetch only 'Read' notifications
        return Notification::where('user_id', Auth::id())
            ->where('status', 'Read')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Display all unread notifications for the authenticated user.
     */
    private function DisplayUnreadNotification()
    {
        // Fetch only 'Unread' notifications
        return Notification::where('user_id', Auth::id())
            ->where('status', 'Unread')
            ->orderBy('created_at', 'desc')
            ->get();
    }
}
