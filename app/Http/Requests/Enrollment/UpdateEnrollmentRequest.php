<?php

namespace App\Http\Requests\Enrollment;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEnrollmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized.
     */
    public function authorize(): bool
    {
        // ตอนนี้ให้ผ่านก่อน
        // ภายหลังจะใช้ EnrollmentPolicy
        return true;
    }

    /**
     * Validation Rules
     */
    public function rules(): array
    {
        return [

            /*
            |--------------------------------------------------------------------------
            | Student
            |--------------------------------------------------------------------------
            */

            'student_id' => [
                'required',
                'integer',
                'exists:students,id',
            ],

            /*
            |--------------------------------------------------------------------------
            | Classroom
            |--------------------------------------------------------------------------
            */

            'classroom_id' => [
                'required',
                'integer',
                'exists:classrooms,id',
            ],

            /*
            |--------------------------------------------------------------------------
            | Academic Term
            |--------------------------------------------------------------------------
            */

            'academic_term_id' => [
                'required',
                'integer',
                'exists:academic_terms,id',
            ],

            /*
            |--------------------------------------------------------------------------
            | Status
            |--------------------------------------------------------------------------
            */

            'status' => [
                'required',
                'string',
                'max:50',
            ],
        ];
    }

    /**
     * Validation Messages
     */
    public function messages(): array
    {
        return [

            'student_id.required' =>
                'กรุณาเลือกนักเรียน',

            'student_id.exists' =>
                'ไม่พบข้อมูลนักเรียน',

            'classroom_id.required' =>
                'กรุณาเลือกห้องเรียน',

            'classroom_id.exists' =>
                'ไม่พบข้อมูลห้องเรียน',

            'academic_term_id.required' =>
                'กรุณาเลือกภาคเรียน',

            'academic_term_id.exists' =>
                'ไม่พบข้อมูลภาคเรียน',

            'status.required' =>
                'กรุณาเลือกสถานะ',
        ];
    }

    /**
     * Custom Attribute Names
     */
    public function attributes(): array
    {
        return [

            'student_id' => 'นักเรียน',

            'classroom_id' => 'ห้องเรียน',

            'academic_term_id' => 'ภาคเรียน',

            'status' => 'สถานะ',
        ];
    }

    /**
     * Prepare data before validation
     */
    protected function prepareForValidation(): void
    {
        $this->merge([

            'student_id' => $this->student_id
                ? (int) $this->student_id
                : null,

            'classroom_id' => $this->classroom_id
                ? (int) $this->classroom_id
                : null,

            'academic_term_id' => $this->academic_term_id
                ? (int) $this->academic_term_id
                : null,

        ]);
    }
}