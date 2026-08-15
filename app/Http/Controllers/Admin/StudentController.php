<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Classroom;
use App\Models\School;
use App\Models\Student;
use App\Models\User;
use App\Services\UserCodeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class StudentController extends Controller
{
    /**
     * ============================================================
     * รายชื่อนักเรียน
     * ============================================================
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        $students = Student::query()
            ->when(
                $user->role !== 'superadmin',
                function ($query) use ($user) {
                    $query->where('school_id', $user->school_id);
                }
            )

            ->with([
                'school:id,school_name',
                'classroom:id,name',
                'user:id,name,email,profile_image,user_code,school_id,external_code',
            ])

            ->when(
                $user->role === 'superadmin' && $request->filled('school_id'),
                function ($query) use ($request) {
                    $query->where('school_id', $request->school_id);
                }
            )

            ->when(
                $request->filled('classroom_id'),
                function ($query) use ($request) {
                    $query->where('classroom_id', $request->classroom_id);
                }
            )

            ->when(
                $request->filled('search'),
                function ($query) use ($request) {

                    $search = trim($request->search);

                    $query->where(function ($subQuery) use ($search) {

                        $subQuery
                            ->where('student_code', 'like', "%{$search}%")
                            ->orWhere('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%")
                            ->orWhere('id_card', 'like', "%{$search}%");
                    });
                }
            )

            ->orderBy('school_id')
            ->orderBy('classroom_id')
            ->orderBy('student_code')
            ->paginate(20)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | โรงเรียน
        |--------------------------------------------------------------------------
        */
        $schools = School::query()
            ->select('id', 'school_name')
            ->when(
                $user->role !== 'superadmin',
                function ($query) use ($user) {
                    $query->where('id', $user->school_id);
                }
            )
            ->orderBy('school_name')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | ห้องเรียน
        |--------------------------------------------------------------------------
        */
        $classrooms = Classroom::query()
            ->when(
                $user->role !== 'superadmin',
                function ($query) use ($user) {
                    $query->where('school_id', $user->school_id);
                }
            )
            ->orderBy('name')
            ->get();

        return view(
            'admin.students.index',
            compact(
                'students',
                'schools',
                'classrooms'
            )
        );
    }


    /**
     * ============================================================
     * ฟอร์มเพิ่มนักเรียน
     * ============================================================
     */
    public function create()
    {
        $user = Auth::user();

        /*
        |--------------------------------------------------------------------------
        | โรงเรียน
        |--------------------------------------------------------------------------
        */
        $schools = School::query()
            ->select('id', 'school_name')
            ->when(
                $user->role !== 'superadmin',
                function ($query) use ($user) {
                    $query->where('id', $user->school_id);
                }
            )
            ->orderBy('school_name')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | ห้องเรียน
        |--------------------------------------------------------------------------
        */
        $classrooms = Classroom::query()
            ->select(
                'id',
                'school_id',
                'name'
            )
            ->when(
                $user->role !== 'superadmin',
                function ($query) use ($user) {
                    $query->where('school_id', $user->school_id);
                }
            )
            ->orderBy('name')
            ->get();

        return view(
            'admin.students.create',
            compact(
                'schools',
                'classrooms'
            )
        );
    }


    /**
     * ============================================================
     * เพิ่มนักเรียน
     *
     * ขั้นตอน:
     *
     * 1. Validate
     * 2. ตรวจ school_id
     * 3. สร้าง User
     * 4. สร้าง Student
     * 5. เชื่อม students.user_id
     * 6. สร้าง user_code
     * 7. Commit
     * ============================================================
     */
    public function store(Request $request)
    {
        $admin = Auth::user();

        $data = $this->validateStudent($request);

        /*
        |--------------------------------------------------------------------------
        | Admin โรงเรียนทั่วไป
        |
        | ห้ามส่งนักเรียนเข้าโรงเรียนอื่น
        |--------------------------------------------------------------------------
        */
        if ($admin->role !== 'superadmin') {
            $data['school_id'] = $admin->school_id;
        }

        /*
        |--------------------------------------------------------------------------
        | ตรวจว่า Classroom อยู่ในโรงเรียนเดียวกัน
        |--------------------------------------------------------------------------
        */
        if (!empty($data['classroom_id'])) {

            $classroomExists = Classroom::query()
                ->where('id', $data['classroom_id'])
                ->where('school_id', $data['school_id'])
                ->exists();

            if (! $classroomExists) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'classroom_id' =>
                            'ห้องเรียนนี้ไม่ได้อยู่ในโรงเรียนที่เลือก'
                    ]);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Transaction
        |--------------------------------------------------------------------------
        */
        DB::beginTransaction();

        try {

            /*
            |--------------------------------------------------------------------------
            | 1. ตรวจว่ามี Student เดิมหรือไม่
            |--------------------------------------------------------------------------
            */
            $existingStudent = Student::query()
                ->where('school_id', $data['school_id'])
                ->where('student_code', $data['student_code'])
                ->first();

            if ($existingStudent) {

                DB::rollBack();

                return back()
                    ->withInput()
                    ->withErrors([
                        'student_code' =>
                            'รหัสนักเรียนนี้มีอยู่ในโรงเรียนนี้แล้ว'
                    ]);
            }


            /*
            |--------------------------------------------------------------------------
            | 2. ตรวจ User เดิมจาก external_code
            |
            | student_code จะถูกใช้เป็น external_code
            |--------------------------------------------------------------------------
            */
            $existingUser = User::query()
                ->where('external_code', $data['student_code'])
                ->first();

            if ($existingUser) {

                DB::rollBack();

                return back()
                    ->withInput()
                    ->withErrors([
                        'student_code' =>
                            'รหัสนักเรียนนี้มีบัญชีผู้ใช้งานอยู่แล้ว'
                    ]);
            }


            /*
            |--------------------------------------------------------------------------
            | 3. สร้างชื่อเต็ม
            |--------------------------------------------------------------------------
            */
            $fullName = trim(
                ($data['prefix'] ?? '') . ' ' .
                ($data['first_name'] ?? '') . ' ' .
                ($data['last_name'] ?? '')
            );

            $fullName = preg_replace('/\s+/', ' ', $fullName);


            /*
            |--------------------------------------------------------------------------
            | 4. สร้าง User
            |--------------------------------------------------------------------------
            |
            | บัญชีนักเรียนจะใช้:
            |
            | role          = student
            | school_id     = โรงเรียนของ Student
            | external_code = รหัสนักเรียน
            |
            |--------------------------------------------------------------------------
            */
            $newUser = new User();

            $newUser->name = $fullName;
            $newUser->role = 'student';

            $newUser->school_id = $data['school_id'];

            $newUser->external_code = $data['student_code'];

            /*
            |--------------------------------------------------------------------------
            | Email
            |--------------------------------------------------------------------------
            |
            | ใช้ email จาก student_code ชั่วคราว
            | เพื่อไม่ให้เกิดปัญหาหาก users.email เป็น required
            |
            | ถ้าในระบบของคุณ email nullable สามารถเปลี่ยนเป็น null ได้
            |--------------------------------------------------------------------------
            */
            $newUser->email =
                'student.' .
                $data['student_code'] .
                '@pspp.local';

            /*
            |--------------------------------------------------------------------------
            | Password เริ่มต้น
            |--------------------------------------------------------------------------
            |
            | ใช้ student_code เป็นรหัสผ่านเริ่มต้น
            |
            | นักเรียนสามารถเปลี่ยนภายหลังได้จาก Profile
            |--------------------------------------------------------------------------
            */
            $newUser->password = Hash::make(
                $data['student_code']
            );

            /*
            |--------------------------------------------------------------------------
            | บันทึก User ก่อน
            |--------------------------------------------------------------------------
            */
            $newUser->save();


            /*
            |--------------------------------------------------------------------------
            | 5. สร้าง User Code
            |--------------------------------------------------------------------------
            |
            | ต้อง load school เพื่อให้ UserCodeService
            | สามารถสร้างรหัสได้ถูกต้อง
            |--------------------------------------------------------------------------
            */
            $newUser->load('school');

            $newUser->user_code =
                UserCodeService::generate($newUser);

            if (! $newUser->user_code) {

                throw new \RuntimeException(
                    'ไม่สามารถสร้าง User Code สำหรับนักเรียนได้'
                );
            }

            $newUser->save();


            /*
            |--------------------------------------------------------------------------
            | 6. สร้าง Student
            |--------------------------------------------------------------------------
            */
            $studentData = $data;

            $studentData['user_id'] = $newUser->id;

            /*
            |--------------------------------------------------------------------------
            | ไม่รับ temple จาก request ในขั้นตอนนี้
            |--------------------------------------------------------------------------
            */
            $studentData['temple_id'] = null;

            $student = Student::create($studentData);


            /*
            |--------------------------------------------------------------------------
            | 7. ตรวจสอบความสัมพันธ์
            |--------------------------------------------------------------------------
            */
            if (! $student->user_id) {

                throw new \RuntimeException(
                    'ไม่สามารถเชื่อมบัญชี User กับ Student ได้'
                );
            }


            /*
            |--------------------------------------------------------------------------
            | 8. Commit
            |--------------------------------------------------------------------------
            */
            DB::commit();


            /*
            |--------------------------------------------------------------------------
            | สำเร็จ
            |--------------------------------------------------------------------------
            */
            return redirect()
                ->route('admin.students.index')
                ->with(
                    'success',
                    'เพิ่มนักเรียนเรียบร้อยแล้ว และสร้างบัญชีผู้ใช้งานให้เรียบร้อยแล้ว'
                );

        } catch (\Throwable $e) {

            DB::rollBack();

            report($e);

            return back()
                ->withInput()
                ->withErrors([
                    'student_code' =>
                        'ไม่สามารถเพิ่มนักเรียนได้: ' .
                        $e->getMessage()
                ]);
        }
    }


    /**
     * ============================================================
     * แก้ไขนักเรียน
     * ============================================================
     */
    public function edit(Student $student)
    {
        $admin = Auth::user();

        /*
        |--------------------------------------------------------------------------
        | ตรวจสิทธิ์โรงเรียน
        |--------------------------------------------------------------------------
        */
        if (
            $admin->role !== 'superadmin' &&
            $student->school_id != $admin->school_id
        ) {
            abort(403);
        }

        /*
        |--------------------------------------------------------------------------
        | โหลด User
        |--------------------------------------------------------------------------
        */
        $student->load([
            'user:id,name,email,profile_image,user_code,school_id,external_code',
            'school:id,school_name',
            'classroom:id,name',
            'temple',
        ]);

        /*
        |--------------------------------------------------------------------------
        | โรงเรียน
        |--------------------------------------------------------------------------
        */
        $schools = School::query()
            ->select(
                'id',
                'school_name'
            )
            ->when(
                $admin->role !== 'superadmin',
                function ($query) use ($admin) {
                    $query->where(
                        'id',
                        $admin->school_id
                    );
                }
            )
            ->orderBy('school_name')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | ห้องเรียน
        |--------------------------------------------------------------------------
        */
        $classrooms = Classroom::query()
            ->select(
                'id',
                'school_id',
                'name'
            )
            ->when(
                $admin->role !== 'superadmin',
                function ($query) use ($admin) {
                    $query->where(
                        'school_id',
                        $admin->school_id
                    );
                })
            ->orderBy('name')
            ->get();

        return view(
            'admin.students.edit',
            compact(
                'student',
                'schools',
                'classrooms'
            )
        );
    }


    /**
     * ============================================================
     * Update นักเรียน
     * ============================================================
     */
    public function update(
        Request $request,
        Student $student
    ) {
        $admin = Auth::user();

        /*
        |--------------------------------------------------------------------------
        | ตรวจสิทธิ์
        |--------------------------------------------------------------------------
        */
        if (
            $admin->role !== 'superadmin' &&
            $student->school_id != $admin->school_id
        ) {
            abort(403);
        }

        $data = $this->validateStudent(
            $request,
            $student
        );

        /*
        |--------------------------------------------------------------------------
        | Admin โรงเรียน
        |--------------------------------------------------------------------------
        */
        if ($admin->role !== 'superadmin') {
            $data['school_id'] = $admin->school_id;
        }

        /*
        |--------------------------------------------------------------------------
        | ตรวจ Classroom
        |--------------------------------------------------------------------------
        */
        if (!empty($data['classroom_id'])) {

            $classroomExists = Classroom::query()
                ->where('id', $data['classroom_id'])
                ->where('school_id', $data['school_id'])
                ->exists();

            if (! $classroomExists) {

                return back()
                    ->withInput()
                    ->withErrors([
                        'classroom_id' =>
                            'ห้องเรียนนี้ไม่ได้อยู่ในโรงเรียนที่เลือก'
                    ]);
            }
        }

        DB::beginTransaction();

        try {

            /*
            |--------------------------------------------------------------------------
            | จำค่าเดิม
            |--------------------------------------------------------------------------
            */
            $oldSchoolId = $student->school_id;
            $oldStudentCode = $student->student_code;

            /*
            |--------------------------------------------------------------------------
            | Update Student
            |--------------------------------------------------------------------------
            */
            $student->update([
                'school_id'     => $data['school_id'],
                'classroom_id'  => $data['classroom_id'] ?? null,
                'student_code'  => $data['student_code'],
                'prefix'        => $data['prefix'] ?? null,
                'first_name'    => $data['first_name'],
                'last_name'     => $data['last_name'],
                'id_card'       => $data['id_card'] ?? null,
                'birth_date'    => $data['birth_date'] ?? null,
                'nationality'   => $data['nationality'] ?? null,
                'ethnicity'    => $data['ethnicity'] ?? null,
                'temple_id'     => null,
            ]);


            /*
            |--------------------------------------------------------------------------
            | ถ้ามี User อยู่แล้ว → Sync
            |--------------------------------------------------------------------------
            */
            if ($student->user_id) {

                $studentUser = User::find(
                    $student->user_id
                );

                if ($studentUser) {

                    $fullName = trim(
                        ($data['prefix'] ?? '') . ' ' .
                        ($data['first_name'] ?? '') . ' ' .
                        ($data['last_name'] ?? '')
                    );

                    $fullName =
                        preg_replace(
                            '/\s+/',
                            ' ',
                            $fullName
                        );

                    $studentUser->name = $fullName;

                    /*
                    |--------------------------------------------------------------------------
                    | school_id ต้องตรงกับ Student
                    |--------------------------------------------------------------------------
                    */
                    $studentUser->school_id =
                        $student->school_id;

                    /*
                    |--------------------------------------------------------------------------
                    | external_code = student_code
                    |--------------------------------------------------------------------------
                    */
                    $studentUser->external_code =
                        $student->student_code;

                    /*
                    |--------------------------------------------------------------------------
                    | สร้าง user_code ใหม่เมื่อจำเป็น
                    |--------------------------------------------------------------------------
                    */
                    $studentUser->load('school');

                    $newUserCode =
                        UserCodeService::generate(
                            $studentUser
                        );

                    if ($newUserCode) {
                        $studentUser->user_code =
                            $newUserCode;
                    }

                    $studentUser->save();
                }
            }


            /*
            |--------------------------------------------------------------------------
            | กรณี Student มีอยู่แล้ว แต่ user_id หาย
            |
            | สร้าง User กลับให้
            |--------------------------------------------------------------------------
            */
            else {

                /*
                |--------------------------------------------------------------------------
                | ตรวจ User จาก external_code
                |--------------------------------------------------------------------------
                */
                $studentUser = User::query()
                    ->where(
                        'external_code',
                        $student->student_code
                    )
                    ->first();

                if (! $studentUser) {

                    $fullName = trim(
                        ($data['prefix'] ?? '') . ' ' .
                        ($data['first_name'] ?? '') . ' ' .
                        ($data['last_name'] ?? '')
                    );

                    $fullName =
                        preg_replace(
                            '/\s+/',
                            ' ',
                            $fullName
                        );

                    $studentUser = new User();

                    $studentUser->name = $fullName;
                    $studentUser->role = 'student';

                    $studentUser->school_id =
                        $student->school_id;

                    $studentUser->external_code =
                        $student->student_code;

                    $studentUser->email =
                        'student.' .
                        $student->student_code .
                        '@pspp.local';

                    $studentUser->password =
                        Hash::make(
                            $student->student_code
                        );

                    $studentUser->save();

                    $studentUser->load('school');

                    $studentUser->user_code =
                        UserCodeService::generate(
                            $studentUser
                        );

                    if (! $studentUser->user_code) {

                        throw new \RuntimeException(
                            'ไม่สามารถสร้าง User Code ได้'
                        );
                    }

                    $studentUser->save();
                }

                /*
                |--------------------------------------------------------------------------
                | เชื่อมกลับ
                |--------------------------------------------------------------------------
                */
                $student->user_id =
                    $studentUser->id;

                $student->save();
            }


            DB::commit();

            return redirect()
                ->route('admin.students.index')
                ->with(
                    'success',
                    'แก้ไขข้อมูลนักเรียนเรียบร้อยแล้ว'
                );

        } catch (\Throwable $e) {

            DB::rollBack();

            report($e);

            return back()
                ->withInput()
                ->withErrors([
                    'student_code' =>
                        'ไม่สามารถแก้ไขข้อมูลนักเรียนได้: ' .
                        $e->getMessage()
                ]);
        }
    }


    /**
     * ============================================================
     * ลบนักเรียน
     * ============================================================
     */
    public function destroy(Student $student)
    {
        $admin = Auth::user();

        /*
        |--------------------------------------------------------------------------
        | ตรวจสิทธิ์
        |--------------------------------------------------------------------------
        */
        if (
            $admin->role !== 'superadmin' &&
            $student->school_id != $admin->school_id
        ) {
            abort(403);
        }

        /*
        |--------------------------------------------------------------------------
        | หากมี User อยู่แล้ว ไม่อนุญาตให้ลบ Student
        |
        | เพื่อป้องกันบัญชี User เสียความสัมพันธ์
        |--------------------------------------------------------------------------
        */
        if ($student->user_id) {

            return back()->with(
                'error',
                'ไม่สามารถลบนักเรียนรายนี้ได้ เพราะเชื่อมกับบัญชีผู้ใช้แล้ว'
            );
        }

        $student->delete();

        return redirect()
            ->route('admin.students.index')
            ->with(
                'success',
                'ลบรายชื่อนักเรียนเรียบร้อยแล้ว'
            );
    }


    /**
     * ============================================================
     * Validation
     * ============================================================
     */
    private function validateStudent(
        Request $request,
        ?Student $student = null
    ): array {

        $studentId = $student?->id;

        return $request->validate([

            'school_id' => [
                'required',
                'exists:schools,id',
            ],

            'classroom_id' => [
                'nullable',
                'exists:classrooms,id',
            ],

            'student_code' => [
                'required',
                'string',
                'max:255',
                'unique:students,student_code,' .
                    $studentId,
            ],

            'prefix' => [
                'nullable',
                'string',
                'max:255',
            ],

            'first_name' => [
                'required',
                'string',
                'max:255',
            ],

            'last_name' => [
                'required',
                'string',
                'max:255',
            ],

            'id_card' => [
                'nullable',
                'string',
                'max:255',
            ],

            'birth_date' => [
                'nullable',
                'date',
            ],

            'nationality' => [
                'nullable',
                'string',
                'max:255',
            ],

            'ethnicity' => [
                'nullable',
                'string',
                'max:255',
            ],
        ]);
    }
}