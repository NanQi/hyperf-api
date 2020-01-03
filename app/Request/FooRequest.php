<?php

declare(strict_types=1);

namespace App\Request;

class FooRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $foo = $this->request->input('foo');
        $bar = $this->request->input('bar');

        if ($foo == '123') {
            $this->retError(403, 'foo不能为123');
        }

        if ($bar == '234') {
            $this->retError(403, 'bar不能为234');
        }

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'foo' => 'required|max:255',
            'bar' => 'required',
        ];
    }

}
