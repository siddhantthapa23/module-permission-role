<?php

namespace Modules\Administration\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class RoleStoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "roles" => "required|array|min:1",
            "roles.*" => "required|integer|distinct",
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
            'roles.required' => 'Role field is required.',
        ];
    }
}