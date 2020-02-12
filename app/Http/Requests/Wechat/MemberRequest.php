<?php

namespace App\Http\Requests\Wechat;

class MemberRequest extends BaseRequest
{
    public function rules()
    {
        $rules = [
            'changeMobile' => [
                'username' => ['required', 'string', 'unique:members'],
            ],
            'changePassword' => [
                'password' => ['required', 'string', 'max:16', 'min:8']
            ]
        ];

        return $rules[$this->action];
    }

    public function messages()
    {
        $message = [
            'changeMobile' => [
                'username.required' => '手机号码不能为空',
                'username.string' => '手机号码数据格式不正确',
                'username.unique' => '手机号码已被注册',
            ],
            'changePassword' => [
                'password.required' => '密码不能为空',
                'password.string' => '密码数据格式不正确',
                'password.max' => '密码长度超出最大限制(16位)',
                'password.min' => '密码长度未达到最小限制(8位)',
            ]
        ];

        return $message[$this->action];
    }
}
