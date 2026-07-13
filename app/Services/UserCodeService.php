<?php

namespace App\Services;

use App\Models\User;

class UserCodeService
{
    /**
     * สร้าง user_code
     *
     * รูปแบบ
     * TCH-03065401-T650123
     * STD-03065401-S670001
     * STF-03065401-STF005
     * DRT-03065401-DIR001
     */
    public static function generate(User $user): ?string
    {
        // ต้องมีข้อมูลครบ
        if (
            empty($user->role) ||
            empty($user->external_code) ||
            empty($user->school)
        ) {
            return null;
        }

        $prefix = match ($user->role) {
            'teacher'  => 'TCH',
            'student'  => 'STD',
            'staff'    => 'STF',
            'director' => 'DRT',
            default    => null,
        };

        if (!$prefix) {
            return null;
        }

        $schoolCode = $user->school->school_code;

        if (empty($schoolCode)) {
            return null;
        }

        return sprintf(
            '%s-%s-%s',
            $prefix,
            $schoolCode,
            $user->external_code
        );
    }
}