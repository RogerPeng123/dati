<?php

namespace App\Http\Requests\Wechat;

class CycleRequest extends BaseRequest
{
    public function rules()
    {
        $rules = [
            'quetionSubmit' => [
                'qc_id' => ['required', 'integer'],
                'body' => ['required', 'json']
            ]
        ];

        return $rules[$this->action];
    }

    public function messages()
    {
        $message = [
            'quetionSubmit' => [
                'qc_id.required' => '期题编号不能为空',
                'qc_id.integer' => '期题编号数据格式不正确',
                'body.required' => '答题提交数据不能为空',
                'body.jaon' => '答题提交数据格式不正确'
            ]
        ];

        return $message[$this->action];
    }
}
