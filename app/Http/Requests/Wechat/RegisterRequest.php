<?php

namespace App\Http\Requests\Wechat;

use App\Exceptions\ApiResponseExceptions;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
        $rules = [
            'username' => ['required', 'string', 'unique:members'],
            'nickname' => ['required', 'string', 'unique:members'],
            'password' => ['required', 'string']
        ];

        return $rules;
    }

    public function messages()
    {
        $message = [
            'username.required' => '手机号码必填',
            'username.string' => '手机号码数据类型不对',
            'username.unique' => '手机号码已被注册',
            'nickname.required' => '昵称必填',
            'nickname.string' => '用户昵称数据格式不对',
            'nickname.unique' => '该昵称已被注册',
            'password.required' => '密码不能为空',
            'password.string' => '密码数据格式不正确'
        ];

        return $message;
    }

    /**
     * 实现User模块的数据校验错误异常
     * Author: roger peng
     * Time: 2019-08-08 15:13
     * @param Validator $validator
     * @throws ApiResponseExceptions
     */
    protected function failedValidation(Validator $validator)
    {
        throw new ApiResponseExceptions($validator->errors()->first());
    }
}
