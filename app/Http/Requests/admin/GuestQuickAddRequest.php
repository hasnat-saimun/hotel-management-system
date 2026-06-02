<?php

namespace App\Http\Requests\admin;

use Illuminate\Foundation\Http\FormRequest;

class GuestQuickAddRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'full_name' => $this->normalizeValue($this->input('full_name')),
            'phone' => $this->normalizeValue($this->input('phone')),
            'email' => $this->normalizeValue($this->input('email')),
            'address' => $this->normalizeValue($this->input('address')),
            'nationality' => $this->normalizeValue($this->input('nationality')),
            'id_number' => $this->normalizeValue($this->input('id_number')),
        ]);
    }

    public function rules(): array
    {
        return [
            'full_name' => ['required', 'string', 'min:3', 'max:191'],
            'phone' => ['required', 'string', 'min:3', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'address' => ['nullable', 'string', 'max:2000'],
            'nationality' => ['nullable', 'string', 'max:191'],
            'id_number' => ['nullable', 'string', 'max:255'],
            'vip' => ['nullable', 'boolean'],
            'blacklisted' => ['nullable', 'boolean'],
        ];
    }

    private function normalizeValue(mixed $value): ?string
    {
        $value = is_string($value) ? trim($value) : $value;

        if ($value === '' || $value === null) {
            return null;
        }

        return is_string($value) ? preg_replace('/\s+/', ' ', $value) : (string) $value;
    }
}