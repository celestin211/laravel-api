<?php

namespace App\Listeners;

use App\Events\OfferPublished;
use App\Models\User;
use App\Notifications\OfferPublishedNotification;
use Illuminate\Support\Facades\Notification;

/**
 * Listener to notify admins when an offer is published.
 */
class NotifyAdminOfferPublished
{
    /**
     * Handle the event.
     */
    public function handle(OfferPublished $event): void
    {
        // Get all admin users (in a real app, you'd have a role/permission system)
        // For now, we'll notify all users or you can filter by a specific role
        $admins = User::all(); // In production, use: User::where('role', 'admin')->get();

        if ($admins->isNotEmpty()) {
            Notification::send($admins, new OfferPublishedNotification($event->offer));
        }
    }
}
