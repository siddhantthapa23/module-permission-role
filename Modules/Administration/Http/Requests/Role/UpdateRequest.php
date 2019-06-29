<?php

namespace Modules\Administration\Http\Requests\Role;

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
        return [
            'name' => 'required|min:5|unique:roles,name,'.$this->getSegmentFromEnd().',id',
        ];
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