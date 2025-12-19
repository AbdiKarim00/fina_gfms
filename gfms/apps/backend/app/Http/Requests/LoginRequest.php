<?php

namespace App\Http\Requests;

use App\Rules\ValidPersonalNumber;
use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'personal_number' => ['required', 'string', new ValidPersonalNumber, 'exists:App\Models\User,personal_number'],
            'password' => ['required', 'string', 'min:8'],
            'otp_channel' => ['sometimes', 'string', 'in:email,sms'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'personal_number.required' => 'Personal number is required',
            'personal_number.exists' => 'Personal number not found',
            'password.required' => 'Password is required',
            'password.min' => 'Password must be at least 8 characters',
            'otp_channel.in' => 'OTP channel must be either email or sms',
        ];
    }
}
