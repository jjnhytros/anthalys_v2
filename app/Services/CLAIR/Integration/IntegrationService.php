<?php

namespace App\Services\CLAIR\Integration;

use App\Models\CLAIR;
use App\Models\City\Message;

class IntegrationService
{
    protected $type = 'I'; // I per Integration

    public function sendNotification($senderId, $recipientId, $subject, $message)
    {
        $notification = Message::create([
            'sender_id' => $senderId,
            'recipient_id' => $recipientId,
            'subject' => $subject,
            'message' => $message,
            'is_notification' => true,
            'status' => 'unread'
        ]);

        CLAIR::logActivity($this->type, 'sendNotification', [
            'subject' => $subject,
            'message_id' => $notification->id,
        ], null, $notification->id, 'sent', 'Notifica inviata con successo');
    }
}
