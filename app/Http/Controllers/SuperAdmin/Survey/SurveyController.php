<?php

namespace App\Http\Controllers\SuperAdmin\Survey;

use App\Http\Controllers\Controller;
use App\Models\Survey\Survey;
use App\Models\PsppEvaluation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class SurveyController extends Controller
{
    /**
     * แสดงรายการแบบสอบถาม
     */
    public function index(): View
    {
        $surveys = Survey::with([
                'creator',
                'school',
            ])
            ->latest()
            ->paginate(15);

        return view('superadmin.surveys.index', compact('surveys'));
    }

    /**
     * แสดงฟอร์มสร้างแบบสอบถาม
     */
    public function create(): View
    {
        return view('superadmin.surveys.create');
    }

    /**
     * บันทึกแบบสอบถามใหม่
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([

            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'objective'   => 'nullable|string',
            'target_type' => 'required|string',

            'start_at'    => 'nullable|date',

            'end_at'      => 'nullable|date|after_or_equal:start_at',

            'is_public'   => 'nullable|boolean',

        ]);

        Survey::create([

            'school_id'   => null,

            'title'       => $validated['title'],

            'description' => $validated['description'] ?? null,

            'objective'   => $validated['objective'] ?? null,

            'target_type' => $validated['target_type'],

            'status'      => 'draft',

            'is_public'   => $request->boolean('is_public'),

            'start_at'    => $validated['start_at'] ?? null,

            'end_at'      => $validated['end_at'] ?? null,

            'created_by'  => Auth::id(),

        ]);

        return redirect()
            ->route('superadmin.surveys.index')
            ->with('success', 'สร้างแบบสอบถามเรียบร้อยแล้ว');
    }
        /**
     * แสดงรายละเอียดแบบสอบถาม
     */
    public function show(Survey $survey): View
    {
        $survey->load([
            'creator',
            'school',
            'sections.questions.options',
        ]);

        return view('superadmin.surveys.show', compact('survey'));
    }

    /**
     * แสดงฟอร์มแก้ไขแบบสอบถาม
     */
    public function edit(Survey $survey): View
    {
        return view('superadmin.surveys.edit', compact('survey'));
    }

    /**
     * บันทึกการแก้ไขแบบสอบถาม
     */
    public function update(Request $request, Survey $survey): RedirectResponse
    {
        $validated = $request->validate([

            'title'       => 'required|string|max:255',

            'description' => 'nullable|string',

            'objective'   => 'nullable|string',

            'target_type' => 'required|string',

            'status'      => 'required|string',

            'start_at'    => 'nullable|date',

            'end_at'      => 'nullable|date|after_or_equal:start_at',

            'is_public'   => 'nullable|boolean',

        ]);

        $survey->update([

            'title'       => $validated['title'],

            'description' => $validated['description'] ?? null,

            'objective'   => $validated['objective'] ?? null,

            'target_type' => $validated['target_type'],

            'status'      => $validated['status'],

            'is_public'   => $request->boolean('is_public'),

            'start_at'    => $validated['start_at'] ?? null,

            'end_at'      => $validated['end_at'] ?? null,

        ]);

        return redirect()
            ->route('superadmin.surveys.index')
            ->with('success', 'แก้ไขแบบสอบถามเรียบร้อยแล้ว');
    }

    /**
     * ลบแบบสอบถาม
     */
    public function destroy(Survey $survey): RedirectResponse
    {
        $survey->delete();

        return redirect()
            ->route('superadmin.surveys.index')
            ->with('success', 'ลบแบบสอบถามเรียบร้อยแล้ว');
    }
        /**
     * แสดงแบบประเมิน PSPP
     */
    public function psppEvaluation(): View
    {
        return view('survey.pspp-evaluation');
    }

    /**
     * บันทึกแบบประเมิน PSPP
     */
    public function submitPsppEvaluation(Request $request): RedirectResponse
    {
        $request->validate([
            'answer' => 'required|array',
        ]);

        // ป้องกันการตอบซ้ำ
        if (PsppEvaluation::where('user_id', Auth::id())->exists()) {

            return redirect()
                ->route('survey.pspp.evaluation')
                ->with('warning', 'ท่านได้ตอบแบบประเมินนี้แล้ว');

        }

        $user = Auth::user();

        $evaluation = new PsppEvaluation();

        // ข้อมูลผู้ตอบ
        $evaluation->user_id = $user->id;

        $evaluation->school_id = $user->school_id;

        $evaluation->school_name = optional($user->school)->name;

        $evaluation->role = $user->role;

        // กรณีนักเรียน
        $evaluation->class_level = null;
        $evaluation->student_code = null;

        if ($user->role === 'student') {

            if (isset($user->student)) {

                $evaluation->class_level = optional($user->student->classroom)->level;

                $evaluation->student_code = $user->student->student_code;

            }

        }

        // คะแนนทั้ง 23 ข้อ
        for ($i = 1; $i <= 23; $i++) {

            $evaluation->{"answer{$i}"} = $request->answer[$i] ?? null;

        }

        // ข้อเสนอแนะ
        $evaluation->suggestion = $request->suggestion;

        $evaluation->submitted_at = now();

        $evaluation->save();

        return redirect()
            ->route('survey.pspp.evaluation')
            ->with('success', 'ขอบพระคุณที่ตอบแบบประเมินเรียบร้อยแล้ว');
    }
}