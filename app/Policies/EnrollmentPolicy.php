<?php

namespace App\Policies;

use App\Models\Enrollment;
use App\Models\User;

class EnrollmentPolicy
{
    /**
     * ดูรายการ
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole([
            User::ROLE_SUPER_ADMIN,
            User::ROLE_ADMIN,
            User::ROLE_DIRECTOR,
        ]);
    }

    /**
     * ดูข้อมูล
     */
    public function view(User $user, Enrollment $enrollment): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        return $user->hasRole([
                User::ROLE_ADMIN,
                User::ROLE_DIRECTOR,
            ])
            && $user->canAccessSchool($enrollment->school_id);
    }

    /**
     * สร้าง
     */
    public function create(User $user): bool
    {
        return $user->hasRole([
            User::ROLE_SUPER_ADMIN,
            User::ROLE_ADMIN,
            User::ROLE_DIRECTOR,
        ]);
    }

    /**
     * แก้ไข
     */
    public function update(User $user, Enrollment $enrollment): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        return $user->hasRole([
                User::ROLE_ADMIN,
                User::ROLE_DIRECTOR,
            ])
            && $user->canAccessSchool($enrollment->school_id);
    }

    /**
     * ลบ
     */
    public function delete(User $user, Enrollment $enrollment): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        return $user->hasRole([
                User::ROLE_ADMIN,
                User::ROLE_DIRECTOR,
            ])
            && $user->canAccessSchool($enrollment->school_id);
    }

    /**
     * Restore
     */
    public function restore(User $user, Enrollment $enrollment): bool
    {
        return false;
    }

    /**
     * Force Delete
     */
    public function forceDelete(User $user, Enrollment $enrollment): bool
    {
        return false;
    }
}