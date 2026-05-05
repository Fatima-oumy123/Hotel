<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRoomRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $roomId = $this->route('room')?->id;

        return [
            'number' => 'required|string|max:10|unique:rooms,number,'.$roomId,
            'room_type_id' => 'required|exists:room_types,id',
            'floor' => 'required|integer|min:0|max:50',
            'status' => 'required|in:available,reserved,occupied,maintenance',
            'notes' => 'nullable|string|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'number.unique' => 'Ce numéro de chambre existe déjà.',
            'room_type_id.exists' => 'Le type de chambre sélectionné est invalide.',
        ];
    }
}
