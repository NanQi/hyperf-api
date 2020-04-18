<?php

declare(strict_types=1);

namespace App\Request;

class TestRequest extends BaseRequest
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
            'name' => 'required|max:255',
            'phone' => 'required',
            'gender' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => '请输入姓名',
            'phone.required'  => '请输入手机号',
            'gender.required'  => '请输入性别',
        ];
    }
}
