<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEmployeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $employeeId = $this->route('employee')?->id;

        return [
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|email|unique:employees,email,'.$employeeId,
            'phone' => 'required|string|max:30',
            'position' => 'required|string|max:100',
            'department' => 'required|string|max:100',
            'salary' => 'nullable|numeric|min:0',
            'hire_date' => 'required|date|before_or_equal:today',
            'id_number' => 'nullable|string|max:50',
            'contract_type' => 'nullable|string|in:CDI,CDD,Intérim,Stage',
            'status' => 'nullable|in:active,inactive,on_leave',
        ];
    }
}
