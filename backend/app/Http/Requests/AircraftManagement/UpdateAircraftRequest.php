<?php
namespace App\Http\Requests\AircraftManagement;
use Illuminate\Foundation\Http\FormRequest;
class UpdateAircraftRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array
    {
        return [
            'code' => ['sometimes', 'string', 'max:64'],
            'name' => ['sometimes', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'],
            'status' => ['sometimes', 'string', 'max:64'],
            'metadata' => ['nullable', 'array'],
            'effective_from' => ['nullable', 'date'],
            'effective_to' => ['nullable', 'date', 'after_or_equal:effective_from'],
            'is_active' => ['nullable', 'boolean'],
            'priority' => ['nullable', 'integer', 'min:0', 'max:10'],
            'notes' => ['nullable', 'string', 'max:5000'],
            'region' => ['nullable', 'string', 'max:64'],
            'station_code' => ['nullable', 'string', 'max:8'],
            'external_ref' => ['nullable', 'string', 'max:128'],
        ];
    }
}
