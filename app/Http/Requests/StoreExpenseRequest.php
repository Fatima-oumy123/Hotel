<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreExpenseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:200',
            'description' => 'nullable|string|max:1000',
            'amount' => 'required|numeric|min:0',
            'category' => 'required|string|max:100',
            'supplier' => 'nullable|string|max:200',
            'expense_date' => 'required|date|before_or_equal:today',
            'receipt' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ];
    }

    public function messages(): array
    {
        return [
            'expense_date.before_or_equal' => 'La date de la dépense ne peut pas être dans le futur.',
            'receipt.mimes' => 'Le reçu doit être un fichier PDF, JPG ou PNG.',
            'receipt.max' => 'Le fichier ne doit pas dépasser 5 Mo.',
        ];
    }
}
