<?php

namespace App\Http\Requests\Wechat;

use App\Exceptions\ApiResponseExceptions;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
            'username' => ['required', 'string'],
            'password' => ['required', 'string']
        ];
    }

    public function messages()
    {
        return [
            'username.required' => '手机号码必填',
            'username.string' => '手机号码数据类型不对',
            'password.required' => '密码不能为空',
            'password.string' => '密码数据格式不正确'
        ];
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
