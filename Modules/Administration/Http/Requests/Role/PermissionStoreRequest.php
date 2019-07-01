<?php

namespace Modules\Administration\Http\Requests\Role;

use Illuminate\Foundation\Http\FormRequest;

class PermissionStoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "permissions" => "required|array|min:1",
            "permissions.*" => "required|integer|distinct",
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

    public function messages()
    {
        return [
            'permissions.required' => 'Atleast one permission is required.',
        ];
    }
}