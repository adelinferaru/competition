<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class QuotesRequest extends FormRequest
{
    protected $validatorErrors;
    protected $validatorErrorMessages;
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
     * Handle a failed validation attempt.
     *
     * @param Validator $validator
     * @return void
     *
     * @throws ValidationException
     */
    protected function failedValidation(Validator $validator)
    {
        $this->validatorErrors = $validator->errors();
        $this->validatorErrorMessages = $validator->getMessageBag()->all();
        $response = response()->json([
            'errors' => $this->validatorErrorMessages
        ], 422);
        throw (new ValidationException($validator, $response));
    }

    public function getErrors()
    {
        return $this->validatorErrors;
    }

    public function getErrorMessages()
    {
        return $this->validatorErrorMessages;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'limit' => 'required|numeric|min:1|max:' . config('quotes.max_retrievable_quotes')
        ];
    }
}
