<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

use App\Models\Province;
use App\Models\School;
use App\Models\User;
use App\Models\Teacher;
use App\Models\Student;

use App\Services\Public\StatisticsService;

class HomeController extends Controller
{
    /**
     * Landing Page
     */
  public function index(StatisticsService $statisticsService)
    {
        // นับผู้เข้าชม
        $statisticsService->increaseVisitor();

        // จังหวัด
        $provinces = Province::orderBy('name_th')->get();

        // ข้อมูลผู้เข้าชม
        $visitor = $statisticsService->get();

        // สถิติระบบ
        $statistics = [

            // โรงเรียน
            'schools' => School::count(),

            // ผู้บริหาร
            'directors' => User::where('role', 'director')->count(),

            // ครู
            'teachers' => User::where('role', 'teacher')->count(),

            // นักเรียน
            'students' => User::where('role', 'student')->count(),

            // เจ้าหน้าที่
            'staffs' => User::where('role', 'staff')->count(),

            // ผู้เข้าชม
            'total_visitors' => $visitor['total_visitors'],

            // วันนี้
            'today_visitors' => $visitor['today_visitors'],

            // ออนไลน์
            'online_users' => $visitor['online_users'],

        ];

        return view('home', compact(
            'provinces',
            'statistics'
        ));
    }
}