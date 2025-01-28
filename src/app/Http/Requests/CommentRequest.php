<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Laravel\Fortify\Rules\Password;
use App\Http\Controllers\CommentController;

class CommentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check(); // Fortify を利用して認証済みユーザーのみ許可
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'content' => ['required', 'string', 'max:255'], // Fortify のルールを適用
        ];
    }

    public function messages()
    {
        return [
            'content.required' => 'コメントを入力してください。',
            'content.max' => 'コメントは255文字以内で入力してください。',
        ];
    }
}
