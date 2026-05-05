<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\EmployeeShift;
use App\Models\EmployeeTask;
use Illuminate\Http\Request;

class EmployeeScheduleController extends Controller
{
    public function index(Request $request)
    {
        $employees = Employee::where('status', 'active')->orderBy('first_name')->get();

        $shifts = EmployeeShift::with('employee')
            ->when($request->date, fn ($q) => $q->whereDate('shift_date', $request->date), fn ($q) => $q->whereDate('shift_date', now()->toDateString()))
            ->orderBy('shift_date')
            ->get();

        $tasks = EmployeeTask::with('employee')
            ->when($request->task_status, fn ($q) => $q->where('status', $request->task_status))
            ->latest()
            ->take(30)
            ->get();

        return view('employee_schedule.index', compact('employees', 'shifts', 'tasks'));
    }

    public function storeShift(Request $request)
    {
        $data = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'shift_date' => 'required|date',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i',
            'attendance_status' => 'required|in:present,absent,rest',
            'notes' => 'nullable|string|max:500',
        ]);

        EmployeeShift::create($data);

        return back()->with('success', 'Horaire ajoute.');
    }

    public function storeTask(Request $request)
    {
        $data = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'title' => 'required|string|max:150',
            'description' => 'nullable|string|max:500',
            'status' => 'required|in:pending,in_progress,done',
            'due_date' => 'nullable|date',
        ]);

        if ($data['status'] === 'done') {
            $data['completed_at'] = now();
        }

        $data['assigned_by'] = auth()->id();

        EmployeeTask::create($data);

        return back()->with('success', 'Tache employee ajoutee.');
    }

    public function updateTask(Request $request, EmployeeTask $task)
    {
        $data = $request->validate([
            'status' => 'required|in:pending,in_progress,done',
        ]);

        if ($data['status'] === 'done' && !$task->completed_at) {
            $data['completed_at'] = now();
        }

        $task->update($data);

        return back()->with('success', 'Statut de la tache mis a jour.');
    }
}
