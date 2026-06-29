<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Subject;
use App\Models\LearningUnit;


class LearningUnitController extends Controller
{


public function index(Subject $subject)
{

    $teacher = Auth::user()->teacher;


    if(!$teacher->subjects->contains($subject->id)){
        abort(403);
    }


    $subject->load('group');


    $units = $subject
        ->units()
        ->orderBy('unit_no')
        ->get();


    $totalHours = $units->sum('hours');


    return view(
        'teacher.subjects.units.index',
        compact(
            'subject',
            'units',
            'totalHours'
        )
    );

}




public function create(Subject $subject)
{

    return view(
        'teacher.subjects.units.create',
        compact('subject')
    );

}




public function store(Request $request, Subject $subject)
{


$request->validate([

    'unit_no'=>'required',
    'unit_name'=>'required',
    'hours'=>'required|numeric'

]);



LearningUnit::create([

    'subject_id'=>$subject->id,

    'unit_no'=>$request->unit_no,

    'unit_name'=>$request->unit_name,

    'hours'=>$request->hours,

    'description'=>$request->description

]);



return redirect()

->route(
'teacher.units.index',
$subject->id
)

->with(
'success',
'เพิ่มหน่วยการเรียนรู้แล้ว'
);


}






public function edit(LearningUnit $unit)
{


return view(
'teacher.subjects.units.edit',
compact('unit')
);


}






public function update(
Request $request,
LearningUnit $unit
)
{


$request->validate([

'unit_no'=>'required',
'unit_name'=>'required',
'hours'=>'required'

]);



$unit->update(

$request->only(
[
'unit_no',
'unit_name',
'hours',
'description'
]
)

);



return back()

->with(
'success',
'แก้ไขเรียบร้อย'
);

}






public function destroy(
LearningUnit $unit
)
{


$unit->delete();


return back()

->with(
'success',
'ลบแล้ว'
);

}


}