<?php

namespace App\Http\Requests\Referral;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class InviteRequest extends FormRequest
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
            'emails' => 'required|array',
            'emails.*' => [
                'string',
                'distinct',
                'email',
                'unique:users,email',
                Rule::unique('user_referral_invites', 'email')
                    ->where(fn ($query) => $query->where('user_id', Auth::user()->id))
            ],
        ];
    }
}
