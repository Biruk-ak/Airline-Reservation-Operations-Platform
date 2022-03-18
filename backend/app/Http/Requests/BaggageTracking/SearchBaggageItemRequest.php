<?php
namespace App\Http\Requests\BaggageTracking;
use Illuminate\Foundation\Http\FormRequest;
class SearchBaggageItemRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array
    {
        return [
            'status' => ['nullable', 'string', 'max:64'],
            'station_code' => ['nullable', 'string', 'max:8'],
            'region' => ['nullable', 'string', 'max:64'],
            'code' => ['nullable', 'string', 'max:64'],
            'name' => ['nullable', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
            'priority_min' => ['nullable', 'integer', 'min:0', 'max:10'],
            'q' => ['nullable', 'string', 'max:255'],
            'effective_on' => ['nullable', 'date'],
            'created_from' => ['nullable', 'date'],
            'created_to' => ['nullable', 'date'],
            'sort' => ['nullable', 'string', 'max:64'],
            'dir' => ['nullable', 'in:asc,desc'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
        ];
    }
}
