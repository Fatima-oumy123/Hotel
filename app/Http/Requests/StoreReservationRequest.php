<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReservationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'room_id' => 'required|exists:rooms,id',
            'guest_first_name' => 'required|string|max:100',
            'guest_last_name' => 'required|string|max:100',
            'guest_dob' => 'nullable|date|before:today',
            'guest_id_number' => 'nullable|string|max:50',
            'guest_phone' => 'required|string|max:20',
            'guest_email' => 'nullable|email|max:150',
            'check_in' => 'required|date|after_or_equal:today',
            'check_out' => 'required|date|after:check_in',
            'adults' => 'required|integer|min:1|max:20',
            'children' => 'nullable|integer|min:0|max:10',
            'special_requests' => 'nullable|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'room_id.required' => 'Veuillez sélectionner une chambre.',
            'check_in.after_or_equal' => 'La date d\'arrivée ne peut pas être dans le passé.',
            'check_out.after' => 'La date de départ doit être après la date d\'arrivée.',
        ];
    }
}
