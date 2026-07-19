<?php

namespace App\Services\Public;

use App\Services\OnlineUserService;

class StatisticsService
{
    /**
     * ที่อยู่ไฟล์ JSON
     */
    protected string $file;

    public function __construct()
    {
        $this->file = storage_path('app/statistics.json');

        if (!file_exists($this->file)) {

            file_put_contents(
                $this->file,
                json_encode([
                    'total_visitors' => 0,
                    'today_visitors' => 0,
                    'online_users'   => 0,
                    'last_reset'     => now()->toDateString(),
                ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
            );

        }
    }

    /**
     * อ่านข้อมูล
     */
    protected function read(): array
    {
        return json_decode(
            file_get_contents($this->file),
            true
        );
    }

    /**
     * บันทึกข้อมูล
     */
    protected function write(array $data): void
    {
        file_put_contents(
            $this->file,
            json_encode(
                $data,
                JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
            )
        );
    }

    /**
     * เพิ่มจำนวนผู้เข้าชม
     */
    public function increaseVisitor(): void
    {
        if (session()->has('visitor_counted')) {
            return;
        }

        $data = $this->read();

        /*
        |--------------------------------------------------------------------------
        | รีเซ็ตยอดวันนี้
        |--------------------------------------------------------------------------
        */

        if ($data['last_reset'] != now()->toDateString()) {

            $data['today_visitors'] = 0;

            $data['last_reset'] = now()->toDateString();

        }

        $data['total_visitors']++;

        $data['today_visitors']++;

        session([
            'visitor_counted' => true
        ]);

        $this->write($data);
    }

    /**
     * จำนวนผู้เข้าชมทั้งหมด
     */
    public function totalVisitors(): int
    {
        return $this->read()['total_visitors'];
    }

    /**
     * จำนวนผู้เข้าชมวันนี้
     */
    public function todayVisitors(): int
    {
        return $this->read()['today_visitors'];
    }

    /**
     * ออนไลน์ 
     */
   public function onlineUsers(): int
    {
        return app(OnlineUserService::class)->count();
    }

    /**
     * คืนค่าทั้งหมด
     */
    public function get(): array
    {
        return [

            'total_visitors' => $this->totalVisitors(),

            'today_visitors' => $this->todayVisitors(),

            'online_users' => $this->onlineUsers(),

        ];
    }
}