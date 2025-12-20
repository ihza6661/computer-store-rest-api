<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'category',
        'message',
        'status',
        'admin_reply',
    ];

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    public function markAsRead()
    {
        $this->update(['status' => 'read']);
        return $this;
    }

    public function markAsReplied()
    {
        $this->update(['status' => 'replied']);
        return $this;
    }
}
