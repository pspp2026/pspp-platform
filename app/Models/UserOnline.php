<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserOnline extends Model
{
    /**
     * ชื่อตาราง
     */
    protected $table = 'user_online';

    /**
     * Mass Assignment
     */
    protected $fillable = [
        'user_id',
        'last_activity',
    ];

    /**
     * Cast
     */
    protected $casts = [
        'last_activity' => 'datetime',
    ];

    /**
     * ความสัมพันธ์กับ User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}