<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\ValidHostname;
use App\Rules\ParseableToken;
use App\Rules\StrongPassword;
use App\Rules\UserPermissions;

class Assignment extends FormRequest
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
            'ip-address' => ['bail', 'required', new ValidHostname],
            'user-token' => [
                'bail',
                'required',
                new ParseableToken,
                new StrongPassword,
                new UserPermissions,
            ],
        ];
    }
}
