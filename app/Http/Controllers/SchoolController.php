<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\School;
use App\Models\Province;

class SchoolController extends Controller
{

    public function index(Request $request)
    {
        $provinces = Province::orderBy('name_th')->get();

        $schools = School::with(['province', 'district', 'subdistrict'])
            ->when($request->province_id, function ($query) use ($request) {
                $query->where('province_id', $request->province_id);
            })
            ->when($request->zone_code, function ($query) use ($request) {
                $query->where('zone_code', $request->zone_code);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('schools.index', compact('schools', 'provinces'));
    }
    /**
     * 🔥 หน้าเดียว: ฟอร์ม + ตาราง
     */
    public function create()
    {
        $provinces = Province::orderBy('name_th')->get();

        // 🔥 ดึงโรงเรียน + relation ครบ
        $schools = School::with(['province','district','subdistrict'])
            ->latest()
            ->paginate(10);

        return view('schools.create', compact('provinces', 'schools'));
    }

    /**
     * บันทึกข้อมูล
     */
    public function store(Request $request)
    {
        $request->validate([
            'school_code'     => 'required|unique:schools,school_code',
            'school_name'     => 'required|string|max:255',
            'province_id'     => 'required',
            'district_id'     => 'required',
            'subdistrict_id'  => 'required',
        ]);

        School::create([
            'school_code'     => $request->school_code,
            'school_name'     => $request->school_name,
            'temple'          => $request->temple,
            'address1'        => $request->address1,
            'address2'        => $request->address2,
            'province_id'     => $request->province_id,
            'district_id'     => $request->district_id,
            'subdistrict_id'  => $request->subdistrict_id,
            'zone_code'            => $request->zone_code,
            'website'         => $request->website,
            'facebook'        => $request->facebook,
            'phone'           => $request->phone,
            'email'           => $request->email,
        ]);

        return redirect()->back()->with('success', 'เพิ่มโรงเรียนเรียบร้อยแล้ว');
    }

    /**
     * แก้ไข
     */
    public function edit($id)
    {
        $school = School::findOrFail($id);
        $provinces = Province::orderBy('name_th')->get();

        return view('schools.edit', compact('school', 'provinces'));
    }

    /**
     * อัปเดต
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'school_code'     => 'required|unique:schools,school_code,' . $id,
            'school_name'     => 'required|string|max:255',
            'province_id'     => 'required',
            'district_id'     => 'required',
            'subdistrict_id'  => 'required',
            'zone_code'      => 'nullable|integer',
        ]);

        $school = School::findOrFail($id);

        $school->update([
            'school_code'     => $request->school_code,
            'school_name'     => $request->school_name,
            'temple'          => $request->temple,
            'address1'        => $request->address1,
            'address2'        => $request->address2,
            'province_id'     => $request->province_id,
            'district_id'     => $request->district_id,
            'subdistrict_id'  => $request->subdistrict_id,
            'zone_code'            => $request->zone_code,
            'website'         => $request->website,
            'facebook'        => $request->facebook,
            'phone'           => $request->phone,
            'email'           => $request->email,
        ]);

        return redirect()->route('superadmin.schools.create')
    ->with('success', 'แก้ไขโรงเรียนเรียบร้อยแล้ว');
    }

    /**
     * ลบโรงเรียน
     */
    public function destroy($id)
    {
        School::findOrFail($id)->delete();

        return redirect()->back()->with('success', 'ลบโรงเรียนเรียบร้อยแล้ว');
    }
}