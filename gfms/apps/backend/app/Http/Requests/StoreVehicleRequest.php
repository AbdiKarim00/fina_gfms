<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreVehicleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Authorization handled by middleware
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $currentYear = date('Y');

        return [
            'registration_number' => ['required', 'string', 'min:6', 'max:15', 'unique:vehicles,registration_number'],
            'make' => ['required', 'string', 'min:2', 'max:50'],
            'model' => ['required', 'string', 'min:2', 'max:50'],
            'year' => ['required', 'integer', 'min:1990', 'max:' . ($currentYear + 1)],
            'fuel_type' => ['required', 'in:petrol,diesel,electric,hybrid'],
            'status' => ['required', 'in:active,inactive,maintenance,disposed'],
            'color' => ['nullable', 'string', 'max:50'],
            'vin' => ['nullable', 'string', 'max:50', 'unique:vehicles,vin'],
            'engine_number' => ['nullable', 'string', 'max:50'],
            'chassis_number' => ['nullable', 'string', 'max:50'],
            'fuel_consumption_rate' => ['nullable', 'numeric', 'min:0', 'max:999.99'],
            'purchase_date' => ['nullable', 'date'],
            'purchase_price' => ['nullable', 'numeric', 'min:0'],
            'purchase_year' => ['nullable', 'integer', 'min:1990', 'max:' . $currentYear],
            'mileage' => ['nullable', 'integer', 'min:0', 'max:1000000'],
            'capacity' => ['nullable', 'integer', 'min:1', 'max:100'],
            'current_location' => ['nullable', 'string', 'max:255'],
            'original_location' => ['nullable', 'string', 'max:255'],
            'responsible_officer' => ['nullable', 'string', 'max:255'],
            'has_log_book' => ['nullable', 'boolean'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'organization_id' => ['nullable', 'exists:organizations,id'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'registration_number.required' => 'Registration number is required',
            'registration_number.unique' => 'This registration number is already in use',
            'registration_number.min' => 'Registration number must be at least 6 characters',
            'make.required' => 'Vehicle make is required',
            'model.required' => 'Vehicle model is required',
            'year.required' => 'Year is required',
            'year.min' => 'Year must be 1990 or later',
            'fuel_type.required' => 'Fuel type is required',
            'fuel_type.in' => 'Fuel type must be petrol, diesel, electric, or hybrid',
            'status.required' => 'Status is required',
            'mileage.max' => 'Mileage seems too high',
            'capacity.min' => 'Capacity must be at least 1',
            'capacity.max' => 'Capacity cannot exceed 100',
        ];
    }
}
