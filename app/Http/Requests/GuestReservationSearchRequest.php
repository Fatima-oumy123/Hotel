<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GuestReservationSearchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'check_in' => 'required|date|after_or_equal:today',
            'check_out' => 'required|date|after:check_in',
            'adults' => 'required|integer|min:1|max:20',
            'children' => 'nullable|integer|min:0|max:10',
            'room_type_id' => 'nullable|exists:room_types,id',
        ];
    }

    public function messages(): array
    {
        return [
            'check_in.after_or_equal' => 'La date d\'arrivée ne peut pas être dans le passé.',
            'check_out.after' => 'La date de départ doit être après la date d\'arrivée.',
            'adults.min' => 'Au moins un adulte est requis.',
        ];
    }
}
