<?php

namespace App\Models\City;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Message extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'sender_id',
        'recipient_id',
        'subject',
        'message',
        'attachments',
        'type',
        'url',
        'is_message',
        'is_notification',
        'is_email',
        'is_archived',
        'status'
    ];

    // Relazione con i cittadini
    public function sender()
    {
        return $this->belongsTo(Citizen::class, 'sender_id');
    }

    public function recipient()
    {
        return $this->belongsTo(Citizen::class, 'recipient_id');
    }
}
