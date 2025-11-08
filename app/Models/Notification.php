<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\DatabaseNotification;

class Notification extends DatabaseNotification
{
    use HasFactory;

    /**
     * Get the notifiable entity (user)
     */
    public function user()
    {
        return $this->morphTo('notifiable');
    }

    /**
     * Mark notification as read
     */
    public function markAsRead()
    {
        $this->update(['read_at' => now()]);
    }

    /**
     * Check if notification is read
     */
    public function isRead()
    {
        return !is_null($this->read_at);
    }

    /**
     * Accessor for title (from data)
     */
    public function getTitleAttribute()
    {
        return $this->data['title'] ?? $this->type;
    }

    /**
     * Accessor for message (from data)
     */
    public function getMessageAttribute()
    {
        return $this->data['message'] ?? '';
    }
}