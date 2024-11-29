
<?php 

namespace App\Http\Requests\Bookings;

use Illuminate\Foundation\Http\FormRequest;

class DistanceFeedRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'jobid' => 'required|integer|exists:jobs,id',
            'flagged' => 'nullable|boolean',
            'admincomment' => 'required_if:flagged,true|string|max:255',
            'distance' => 'nullable|string|max:255',
            'time' => 'nullable|string|max:255',
            'session_time' => 'nullable|string|max:255',
            'manually_handled' => 'nullable|boolean',
            'by_admin' => 'nullable|boolean',
        ];
    }
}