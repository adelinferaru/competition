<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class LeaderBoardRequest extends FormRequest
{
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
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'orderByScore' => [
                'nullable',
                Rule::in(['asc', 'desc'])
            ]
        ];
    }

    protected function prepareForValidation()
    {
        if ($this->has('orderByScore')) {
            $this->merge(['orderByScore' => Str::lower($this->get('orderByScore'))]);
        }
    }
}
