<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketAttachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_id',
        'original_name',
        'file_path',
        'mime_type',
        'size',
        'type',
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }
}
