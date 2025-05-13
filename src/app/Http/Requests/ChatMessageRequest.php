<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChatMessageRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'message' => 'required|string|max:400',
            'image' => 'nullable|image|mimes:jpeg,png', // 画像は任意でjpegかpng形式
        ];
    }

    // エラーメッセージのカスタマイズ
    public function messages()
    {
        return [
            'message.required' => '本文を入力してください',
            'message.max' => '本文は400文字以内で入力してください',
            'image.mimes' => '「.png 」または 「.jpeg 」形式でアップロードしてください',
        ];
    }
}
