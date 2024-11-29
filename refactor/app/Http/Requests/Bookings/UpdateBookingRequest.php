<?php

namespace App\Http\Requests\Bookings;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBookingRequest extends FormRequest
{
    public function authorize()
    {
        return true; 
    }


    public function all($keys = null)
    {
        $data = parent::all($keys);
        $data['job_id'] = $this->route('id');
        return $data;
    }

    public function rules()
    {
        return [
            'job_id' => 'required|exists:jobs,id|integer|gt:0',
                'due'              => ['required', 'date', 'after_or_equal:now'],
                'from_language_id' => ['required', 'integer', 'exists:languages,id'], 
                'admin_comments'   => ['nullable', 'string', 'max:1000'], 
                'reference'        => ['nullable', 'string', 'max:255'],
        ];
    }
}