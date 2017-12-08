<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\ValidHostname;
use App\Rules\ParseableToken;
use App\Rules\StrongPassword;
use App\Rules\UserPermissions;

class Submission extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'host' => ['bail', 'required', 'unique:submissions', new ValidHostname],
            'user_token' => [
                'bail',
                'required',
                new ParseableToken,
                new StrongPassword,
                new UserPermissions,
            ],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'host.unique' => 'This IP address has already been used for a submission.',
        ];
    }
}
