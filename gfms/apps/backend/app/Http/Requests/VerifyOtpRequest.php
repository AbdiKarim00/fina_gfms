<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VerifyOtpRequest extends FormRequest
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
            'user_id' => ['required', 'string', 'exists:users,id'],
            'code' => ['required', 'string', 'digits:6'],
            'otp_channel' => ['sometimes', 'string', 'in:email,sms'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'user_id.required' => 'User ID is required',
            'user_id.exists' => 'User not found',
            'code.required' => 'Verification code is required',
            'code.digits' => 'Verification code must be exactly 6 digits',
            'otp_channel.in' => 'OTP channel must be either email or sms',
        ];
    }
}
