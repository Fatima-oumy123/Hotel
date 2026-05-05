<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $employees = Employee::when($request->department, fn($q) => $q->where('department', $request->department))
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->search, fn($q) => $q->where(function($q2) use ($request) {
                $q2->where('first_name', 'like', "%{$request->search}%")
                   ->orWhere('last_name', 'like', "%{$request->search}%")
                   ->orWhere('email', 'like', "%{$request->search}%");
            }))
            ->latest()
            ->paginate(20);

        $departments = Employee::distinct()->pluck('department');
        $stats = [
            'total'        => Employee::where('status', 'active')->count(),
            'mass_salariale' => Employee::where('status', 'active')->sum('salary'),
            'on_leave'     => Employee::where('status', 'on_leave')->count(),
            'inactive'     => Employee::where('status', 'inactive')->count(),
        ];

        return view('employees.index', compact('employees', 'departments', 'stats'));
    }

    public function create()
    {
        return view('employees.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name'    => 'required|string|max:100',
            'last_name'     => 'required|string|max:100',
            'email'         => 'required|email|unique:employees,email',
            'phone'         => 'required|string|max:20',
            'position'      => 'required|string|max:100',
            'department'    => 'required|string|max:100',
            'salary'        => 'required|numeric|min:0',
            'hire_date'     => 'required|date',
            'contract_type' => 'required|string|max:10',
            'id_number'     => 'nullable|string|max:50',
        ]);

        Employee::create($request->all());
        return redirect()->route('employees.index')->with('success', 'Employé ajouté avec succès.');
    }

    public function show(Employee $employee)
    {
        return view('employees.show', compact('employee'));
    }

    public function edit(Employee $employee)
    {
        return view('employees.edit', compact('employee'));
    }

    public function update(Request $request, Employee $employee)
    {
        $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name'  => 'required|string|max:100',
            'email'      => 'required|email|unique:employees,email,' . $employee->id,
            'phone'      => 'required|string|max:20',
            'position'   => 'required|string|max:100',
            'department' => 'required|string|max:100',
            'salary'     => 'required|numeric|min:0',
            'status'     => 'required|in:active,inactive,on_leave',
        ]);

        $employee->update($request->all());
        return redirect()->route('employees.show', $employee)->with('success', 'Employé mis à jour.');
    }

    public function destroy(Employee $employee)
    {
        $employee->update(['status' => 'inactive', 'end_date' => today()]);
        return redirect()->route('employees.index')->with('success', 'Employé désactivé.');
    }
}
