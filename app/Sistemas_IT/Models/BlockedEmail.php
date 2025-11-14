<?php

namespace App\Sistemas_IT\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class BlockedEmail extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'reason',
        'blocked_by',
    ];

    public function admin()
    {
        return $this->belongsTo(User::class, 'blocked_by');
    }
}
