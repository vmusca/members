<?php

namespace LaravelEnso\Members\app\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class ValidateMemberRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $nameUnique = Rule::unique('members', 'name');

        $nameUnique = $this->has('id')
            ? $nameUnique->ignore($this->get('id'))
            : $nameUnique;

        return [
            'id' => 'nullable|exists:members,id',
            'name' => ['required', $nameUnique],
            'description' => 'string|nullable',
            'userIds' => 'array',
        ];
    }
}
