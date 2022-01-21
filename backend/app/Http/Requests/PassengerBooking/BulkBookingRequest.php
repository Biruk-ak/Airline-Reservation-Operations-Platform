<?php
namespace App\Http\Requests\PassengerBooking;
use Illuminate\Foundation\Http\FormRequest;
class BulkBookingRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array
    {
        return [
            'action' => ['required', 'string', 'in:activate,deactivate,archive,set_priority,set_status'],
            'ids' => ['required', 'array', 'min:1', 'max:500'],
            'ids.*' => ['integer'],
            'payload' => ['nullable', 'array'],
            'payload.reason' => ['nullable', 'string', 'max:1000'],
            'payload.priority' => ['nullable', 'integer', 'min:0', 'max:10'],
            'payload.status' => ['nullable', 'string', 'max:64'],
        ];
    }
}
