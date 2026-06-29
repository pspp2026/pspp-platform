<?php

namespace App\Services;

class GradeService
{
    /**
     * คำนวณเกรดจากคะแนนรวม
     */
    public function calculate(float $score): array
    {
        $grade = '0';
        $gradePoint = 0.00;
        $passed = false;

        if ($score >= 80) {
            $grade = '4';
            $gradePoint = 4.00;
            $passed = true;

        } elseif ($score >= 75) {
            $grade = '3.5';
            $gradePoint = 3.50;
            $passed = true;

        } elseif ($score >= 70) {
            $grade = '3';
            $gradePoint = 3.00;
            $passed = true;

        } elseif ($score >= 65) {
            $grade = '2.5';
            $gradePoint = 2.50;
            $passed = true;

        } elseif ($score >= 60) {
            $grade = '2';
            $gradePoint = 2.00;
            $passed = true;

        } elseif ($score >= 55) {
            $grade = '1.5';
            $gradePoint = 1.50;
            $passed = true;

        } elseif ($score >= 50) {
            $grade = '1';
            $gradePoint = 1.00;
            $passed = true;

        } else {
            $grade = '0';
            $gradePoint = 0.00;
            $passed = false;
        }

        return [
            'grade' => $grade,
            'grade_point' => $gradePoint,
            'passed' => $passed,
        ];
    }
}