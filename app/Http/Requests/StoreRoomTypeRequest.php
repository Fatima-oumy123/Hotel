<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRoomTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $typeId = $this->route('roomType')?->id;

        return [
            'name' => 'required|string|max:100|unique:room_types,name,'.$typeId,
            'capacity' => 'required|integer|min:1|max:20',
            'base_price' => 'required|numeric|min:0',
            'description' => 'nullable|string|max:1000',
            'amenities' => 'nullable|array',
            'amenities.*' => 'string|max:100',
            'image' => 'nullable|image|max:2048',
        ];
    }
}
