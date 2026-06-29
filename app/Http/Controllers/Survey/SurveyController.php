<?php

namespace App\Http\Controllers\Survey;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Survey\Survey;

class SurveyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $surveys = Survey::with('creator', 'school')
            ->latest()
            ->paginate(15);

        return view('survey.index', compact('surveys'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('survey.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
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
            'school_id'   => Auth::user()->school_id,
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
            ->route('survey.index')
            ->with('success', 'สร้างแบบสอบถามเรียบร้อยแล้ว');
    }

    /**
     * Display the specified resource.
     */
    public function show(Survey $survey)
    {
        $survey->load('sections.questions.options');

        return view('survey.show', compact('survey'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Survey $survey)
    {
        return view('survey.edit', compact('survey'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Survey $survey)
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
            ->route('survey.index')
            ->with('success', 'แก้ไขแบบสอบถามเรียบร้อยแล้ว');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Survey $survey)
    {
        $survey->delete();

        return redirect()
            ->route('survey.index')
            ->with('success', 'ลบแบบสอบถามเรียบร้อยแล้ว');
    }

    /**
     * --------------------------------------------------------------------------
     * PSPP Evaluation
     * --------------------------------------------------------------------------
     */

    /**
     * แสดงแบบประเมิน PSPP
     */
    public function psppEvaluation()
    {
        return view('survey.pspp-evaluation');
    }

    /**
     * บันทึกผลการประเมิน PSPP
     */
    public function submitPsppEvaluation(Request $request)
    {
        // ทดสอบการรับข้อมูลก่อน
        dd($request->all());

        // Phase ถัดไป
        // SurveyResponse::create(...)
        // SurveyAnswer::create(...)
    }
}