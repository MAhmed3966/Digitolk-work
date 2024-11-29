<?php

namespace App\Http\Requests\Bookings;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
        $immediate = $this->input('immediate') === 'yes';

        return [
            'from_language_id' => 'required|integer|exists:languages,id',
            'immediate' => 'required|in:yes,no',
            'due_date' => $immediate ? 'nullable' : 'required|date_format:m/d/Y',
            'due_time' => $immediate ? 'nullable' : 'required|date_format:H:i',
            'customer_phone_type' => 'nullable|in:yes,no',
            'customer_physical_type' => 'nullable|in:yes,no',
            'duration' => 'required|integer|min:1',
            'job_for' => 'required|array|min:1',
            'job_for.*' => 'in:male,female,normal,certified,certified_in_law,certified_in_helth',
            'by_admin' => 'nullable|in:yes,no',
        ];
    }
}
