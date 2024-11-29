<?php

namespace App\Http\Requests\Bookings;

use Illuminate\Foundation\Http\FormRequest;

class ShowJobRequest extends FormRequest
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
        ];
    }
}
