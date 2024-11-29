<?php

namespace App\Http\Requests\Bookings;

use Illuminate\Foundation\Http\FormRequest;

class StoreJobEmailRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true; // Change this to implement custom authorization logic if required.
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        return [
            'user_email_job_id' => ['required', 'integer', 'exists:jobs,id'],
            'user_email'        => ['nullable', 'email'],
            'reference'         => ['nullable', 'string', 'max:255'],
            'address'           => ['nullable', 'string', 'max:255'],
            'instructions'      => ['nullable', 'string'],
            'town'              => ['nullable', 'string', 'max:255'],
            'user_type'         => ['required', 'string'],
        ];
        
    }
}
