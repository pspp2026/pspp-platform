<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Director;
use App\Models\Staff;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserApprovalController extends Controller
{
    /**
     * แสดงรายชื่อผู้ใช้รออนุมัติ เฉพาะโรงเรียนของ Admin ที่ Login
     */
    public function index()
    {
        $schoolId = Auth::user()->school_id;

        $users = User::where('status', 'pending')
            ->where('school_id', $schoolId)
            ->orderBy('created_at')
            ->get();

        return view('admin.users.pending', compact('users'));
    }

    /**
     * อนุมัติรายคน เฉพาะผู้ใช้ในโรงเรียนเดียวกัน
     */
    public function approve(Request $request, User $user)
    {
        $schoolId = Auth::user()->school_id;

        abort_if($user->school_id != $schoolId, 403);

        $validated = $request->validate([
            'role' => ['required', 'string', 'in:teacher,student,staff,director'],
        ]);

        $this->approveUser($user, $validated['role']);

        return back()->with(
            'success',
            "อนุมัติ {$user->name} เรียบร้อยแล้ว"
        );
    }

    /**
     * อนุมัติหลายคน เฉพาะผู้ใช้ในโรงเรียนเดียวกัน
     */
    public function approveBulk(Request $request)
    {
        $schoolId = Auth::user()->school_id;

        $validated = $request->validate([
            'user_ids' => ['required', 'array'],
            'user_ids.*' => ['integer'],
            'roles' => ['required', 'array'],
        ]);

        $users = User::whereIn('id', $validated['user_ids'])
            ->where('school_id', $schoolId)
            ->where('status', 'pending')
            ->get()
            ->keyBy('id');

        $count = 0;

        foreach ($validated['user_ids'] as $id) {
            $user = $users->get($id);
            $role = $validated['roles'][$id] ?? null;

            if (!$user || !$role) {
                continue;
            }

            if (!in_array($role, ['teacher', 'student', 'staff', 'director'], true)) {
                continue;
            }

            $this->approveUser($user, $role);

            $count++;
        }

        return back()->with(
            'success',
            "อนุมัติสำเร็จ {$count} คน"
        );
    }

    /**
     * ปฏิเสธผู้ใช้ เฉพาะผู้ใช้ในโรงเรียนเดียวกัน
     */
    public function reject(User $user)
    {
        $schoolId = Auth::user()->school_id;

        abort_if($user->school_id != $schoolId, 403);

        $user->update([
            'status' => 'rejected',
        ]);

        return back()->with(
            'success',
            "ปฏิเสธ {$user->name} เรียบร้อยแล้ว"
        );
    }

    /**
     * Logic กลางในการอนุมัติ
     */
    private function approveUser(User $user, string $role): void
    {
        $user->update([
            'role' => $role,
            'status' => 'approved',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        $user->refresh();

        $this->createProfile($user);
    }

    /**
     * สร้าง Profile ตาม Role
     */
    private function createProfile(User $user): void
    {
        switch ($user->role) {
            case 'teacher':
                $this->createTeacherProfile($user);
                break;

            case 'student':
                $this->createStudentProfile($user);
                break;

            case 'staff':
                $this->createStaffProfile($user);
                break;

            case 'director':
                $this->createDirectorProfile($user);
                break;
        }
    }

    /**
     * สร้าง Teacher Profile
     */
    private function createTeacherProfile(User $user): void
    {
        Teacher::updateOrCreate(
            [
                'user_id' => $user->id,
            ],
            [
                'teacher_code' => $user->external_code,
                'first_name' => $user->name,
                'last_name' => null,
                'prefix' => null,
                'position' => null,
                'department' => null,
                'subject' => null,
                'school_id' => $user->school_id,
                'status' => 'active',
            ]
        );
    }

    /**
     * สร้าง Student Profile
     */
    private function createStudentProfile(User $user): void
    {
        Student::updateOrCreate(
            [
                'user_id' => $user->id,
            ],
            [
                'student_code' => $user->external_code,
                'first_name' => $user->name,
                'last_name' => null,
                'prefix' => null,
                'school_id' => $user->school_id,
                'status' => 'active',
            ]
        );
    }

   /**
 * สร้าง Staff Profile
 */
    private function createStaffProfile(User $user): void
    {
        Staff::updateOrCreate(
            [
                'user_id' => $user->id,
            ],
            [
                'staff_code'  => $user->external_code,
                'prefix'      => null,
                'first_name'  => $user->name,
                'last_name'   => null,
                'position'    => null,
                'department'  => null,
                'school_id'   => $user->school_id,
                'temple_id'   => null,
                'is_monk'     => false,
                'status'      => 'active',
            ]
        );
    }

    /**
     * สร้าง Director Profile
     *
     * หมายเหตุ: หากตาราง directors ของคุณมีชื่อคอลัมน์ต่างจากนี้
     * ให้ส่ง DESCRIBE directors มา แล้วค่อยปรับส่วนนี้
     */
    private function createDirectorProfile(User $user): void
    {
        if (!class_exists(Director::class)) {
            return;
        }

        Director::updateOrCreate(
            [
                'user_id' => $user->id,
            ],
            [
                'director_code' => $user->external_code,
                'school_id' => $user->school_id,
            ]
        );
    }
}