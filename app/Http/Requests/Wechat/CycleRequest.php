<?php

namespace App\Http\Requests\Wechat;

class CycleRequest extends BaseRequest
{
    public function rules()
    {
        $rules = [
            'quetionSubmit' => [
                'body' => ['required', 'json']
            ]
        ];

        return $rules[$this->action];
    }

    public function messages()
    {
        $message = [
            'quetionSubmit' => [
                'body.required' => '答题提交数据不能为空',
                'body.jaon' => '答题提交数据格式不正确'
            ]
        ];

        return $message[$this->action];
    }
}
