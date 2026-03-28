<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\School;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $query = Employee::with('school');

        // 🔍 search
        if ($request->search) {
            $query->where('name_th', 'like', "%{$request->search}%")
                  ->orWhere('employee_code', 'like', "%{$request->search}%");
        }

        $employees = $query->latest()->paginate(10);

        return view('employees.index', compact('employees'));
    }

    public function create()
    {
        $schools = School::all();
        return view('employees.create', compact('schools'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_code' => 'required|unique:employees',
            'name_th' => 'required',
            'school_id' => 'required',
        ]);

        Employee::create($request->all());

        return redirect()->route('employees.index')
            ->with('success', 'เพิ่มพนักงานเรียบร้อย');
    }

    public function edit(Employee $employee)
    {
        $schools = School::all();
        return view('employees.edit', compact('employee', 'schools'));
    }

    public function update(Request $request, Employee $employee)
    {
        $request->validate([
            'employee_code' => 'required|unique:employees,employee_code,' . $employee->id,
            'name_th' => 'required',
        ]);

        $employee->update($request->all());

        return redirect()->route('employees.index')
            ->with('success', 'แก้ไขข้อมูลเรียบร้อย');
    }

    public function destroy(Employee $employee)
    {
        $employee->delete();

        return back()->with('success', 'ลบข้อมูลแล้ว');
    }
}