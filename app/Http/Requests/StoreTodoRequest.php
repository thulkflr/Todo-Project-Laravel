<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTodoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:255'],
            'due_date' => ['nullable', 'date'],
            'attachment' => ['nullable', 'file', 'mimes:pdf', 'max:2048'],
        ];
    }

    public function messages(): array{
        return [
            'title.required' => 'The title field is required.',

        ];
    }

    public function attributes(): array{
        return [
            'title' => 'Title',
            'description' => 'Description',
            'due_date' => 'Due Date',
            'attachment' => 'Attachment',

        ];
    }
}
