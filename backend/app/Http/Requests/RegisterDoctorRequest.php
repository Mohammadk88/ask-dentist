<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterDoctorRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'required|string|max:20',
            'specialization_id' => 'required|exists:specializations,id',
            'license_number' => 'required|string|max:100|unique:doctors',
            'years_of_experience' => 'required|integer|min:0|max:50',
            'education' => 'required|string',
            'certifications' => 'nullable|string',
            'bio' => 'nullable|string|max:1000',
            'consultation_fee' => 'nullable|numeric|min:0|max:9999.99',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'license_number.unique' => 'This license number is already registered.',
            'specialization_id.exists' => 'Selected specialization is invalid.',
            'years_of_experience.max' => 'Years of experience cannot exceed 50.',
            'consultation_fee.max' => 'Consultation fee cannot exceed $9,999.99.',
        ];
    }
}
