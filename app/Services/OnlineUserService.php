<?php

namespace App\Services;

use App\Models\UserOnline;
use Illuminate\Support\Facades\Auth;

class OnlineUserService
{
    /**
     * เวลาที่ถือว่ายังออนไลน์ (นาที)
     */
    protected int $timeout = 35;

    /**
     * อัปเดตการใช้งานล่าสุด
     */
    public function update(): void
    {
        if (!Auth::check()) {
            return;
        }

        UserOnline::updateOrCreate(
            [
                'user_id' => Auth::id(),
            ],
            [
                'last_activity' => now(),
            ]
        );
    }

    /**
     * ลบผู้ที่หมดเวลา
     */
    public function clearExpired(): void
    {
        UserOnline::where(
            'last_activity',
            '<',
            now()->subMinutes($this->timeout)
        )->delete();
    }

    /**
     * จำนวนผู้ใช้ออนไลน์
     */
    public function count(): int
    {
        $this->clearExpired();

        return UserOnline::count();
    }
}