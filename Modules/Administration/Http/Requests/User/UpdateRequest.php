<?php

namespace Modules\Administration\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users,email,'.$this->getSegmentFromEnd().',id',
        ];

        if($this->input('password') != null) {
            $extras = [
                'password' => 'required|confirmed|min:6|max:255',
            ];

            return $rules + $extras;
        }

        return $rules;
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Determine role id from url.
     *
     * @return bool
     */
    private function getSegmentFromEnd($position_from_end = 1) 
    {
        $segments = $this->segments();
        return $segments[sizeof($segments) - $position_from_end];
    }
}