<?php

namespace App\Http\Requests\admin;

use Illuminate\Foundation\Http\FormRequest;

class GuestSearchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'q' => $this->normalizeValue($this->input('q')),
            'name' => $this->normalizeValue($this->input('name')),
            'phone' => $this->normalizeValue($this->input('phone')),
            'email' => $this->normalizeValue($this->input('email')),
        ]);
    }

    public function rules(): array
    {
        return [
            'q' => ['nullable', 'string', 'min:2', 'max:191'],
            'name' => ['nullable', 'string', 'min:2', 'max:191'],
            'phone' => ['nullable', 'string', 'min:3', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'page' => ['nullable', 'integer', 'min:1'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:50'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            if ($this->hasSearchTerm()) {
                return;
            }

            $validator->errors()->add('q', 'Please provide a name, phone number, or email address to search.');
        });
    }

    private function hasSearchTerm(): bool
    {
        return $this->filled('q') || $this->filled('name') || $this->filled('phone') || $this->filled('email');
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