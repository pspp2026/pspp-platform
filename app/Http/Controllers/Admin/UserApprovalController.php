<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Teacher;
use App\Models\Student;
use App\Models\Staff;
use App\Models\Director;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserApprovalController extends Controller
{
    /**
     * แสดงรายชื่อผู้ใช้รออนุมัติ
     */
    public function index()
    {
        $users = User::where('status', 'pending')
            ->orderBy('created_at')
            ->get();

        return view('admin.users.pending', compact('users'));
    }

    /**
     * อนุมัติรายคน
     */
    public function approve(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|string',
        ]);

        $this->approveUser($user, $request->role);

        return back()->with(
            'success',
            "อนุมัติ {$user->name} เรียบร้อยแล้ว"
        );
    }

    /**
     * อนุมัติหลายคน
     */
    public function approveBulk(Request $request)
    {
        $request->validate([
            'user_ids' => 'required|array',
            'roles'    => 'required|array',
        ]);

        $count = 0;

        foreach ($request->user_ids as $id) {

            $user = User::find($id);

            if (!$user) {
                continue;
            }

            $role = $request->roles[$id] ?? null;

            if (!$role) {
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
     * Reject ผู้ใช้
     */
    public function reject(User $user)
    {
        $user->update([
            'status' => 'rejected',
        ]);

        return back()->with(
            'success',
            "ปฏิเสธ {$user->name} เรียบร้อยแล้ว"
        );
    }

    /**
     * -----------------------------
     * Logic กลางในการ Approve
     * -----------------------------
     */
    private function approveUser(User $user, string $role): void
    {
        $user->update([
            'role'        => $role,
            'status'      => 'approved',
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

        }
    }

    /**
     * สร้าง Teacher Profile
     */
    private function createTeacherProfile(User $user): void
    {
        Teacher::updateOrCreate(

            [
                'user_id' => $user->id
            ],

            [
                'teacher_code' => 'T' . str_pad(
                    $user->id,
                    5,
                    '0',
                    STR_PAD_LEFT
                ),

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
      * สร้าง
      */
     private function createStudentProfile(User $user)
    {
        Student::updateOrCreate(

            [
                'user_id' => $user->id
            ],

            [
                'student_code' => 'S' . str_pad(
                    $user->id,
                    5,
                    '0',
                    STR_PAD_LEFT
                ),

                'first_name' => $user->name,

                'last_name' => null,

                'prefix' => null,

                'school_id' => $user->school_id,

                'status' => 'active',
            ]
        );
    }
}