<?php

namespace App\Services\SuperAdmin;

use App\Models\School;
use App\Models\User;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    /**
     * Hero Section
     */
    public function getHeroData(): array
    {
        return [
            'name'      => Auth::user()?->name ?? 'Super Admin',
            'today'     => now(),
            'greeting'  => $this->getGreeting(),
        ];
    }

    /**
     * Dashboard Statistics
     */
    public function getStatistics(): array
    {
        return [
            'schools'  => School::count(),
            'users'    => User::count(),
            'students' => class_exists(Student::class) ? Student::count() : 0,
            'teachers' => class_exists(Teacher::class) ? Teacher::count() : 0,
        ];
    }

    /**
     * Dashboard Charts
     */
    public function getChartData(): array
    {
        return [
            'studentsBySchool' => $this->studentsBySchool(),
            'teachersBySchool' => $this->teachersBySchool(),
        ];
    }

    /**
     * Recent Activities
     */
    public function getRecentActivities(): array
    {
        return [
            [
                'icon'        => '🏫',
                'title'       => 'ระบบพร้อมใช้งาน',
                'description' => 'Activity Log จะเชื่อมต่อใน Phase ถัดไป',
                'time'        => now()->diffForHumans(),
            ],
        ];
    }

    /**
     * System Status
     */
    public function getSystemStatus(): array
    {
        return [
            'php'         => PHP_VERSION,
            'laravel'     => app()->version(),
            'database'    => $this->databaseStatus(),
            'timezone'    => config('app.timezone'),
            'environment' => app()->environment(),
        ];
    }

    /**
     * Quick Actions
     */
    public function getQuickActions(): array
    {
        return [
            [
                'title' => 'เพิ่มโรงเรียน',
                'icon'  => '🏫',
                'route' => '#',
                'color' => 'emerald',
            ],
            [
                'title' => 'เพิ่มผู้ใช้',
                'icon'  => '👤',
                'route' => '#',
                'color' => 'blue',
            ],
            [
                'title' => 'รายงาน',
                'icon'  => '📊',
                'route' => '#',
                'color' => 'amber',
            ],
            [
                'title' => 'ตั้งค่าระบบ',
                'icon'  => '⚙️',
                'route' => '#',
                'color' => 'gray',
            ],
        ];
    }

    /**
     * Notifications
     */
    public function getNotifications(): array
    {
        return [];
    }

    /**
     * Students by School
     */
    protected function studentsBySchool(): array
    {
        if (!class_exists(Student::class)) {
            return [];
        }

        return School::orderBy('id')
            ->get()
            ->map(function ($school) {

                return [
                    'label' => $school->name,
                    'value' => Student::where('school_id', $school->id)->count(),
                ];

            })
            ->toArray();
    }

    /**
     * Teachers by School
     */
    protected function teachersBySchool(): array
    {
        if (!class_exists(Teacher::class)) {
            return [];
        }

        return School::orderBy('id')
            ->get()
            ->map(function ($school) {

                return [
                    'label' => $school->name,
                    'value' => Teacher::where('school_id', $school->id)->count(),
                ];

            })
            ->toArray();
    }

    /**
     * Database Status
     */
    protected function databaseStatus(): string
    {
        try {

            DB::connection()->getPdo();

            return 'Online';

        } catch (\Throwable $e) {

            return 'Offline';

        }
    }

    /**
     * Greeting
     */
    protected function getGreeting(): string
    {
        $hour = now()->hour;

        if ($hour < 12) {
            return 'สวัสดีตอนเช้า';
        }

        if ($hour < 17) {
            return 'สวัสดีตอนบ่าย';
        }

        return 'สวัสดีตอนเย็น';
    }
}