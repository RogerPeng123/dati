<?php


namespace App\Http\Requests\Wechat;

use App\Exceptions\ApiResponseExceptions;
use App\Toolkit\ActionToolkit;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class BaseRequest extends FormRequest
{
    /**
     * @var bool|string
     */
    protected $action;

    public function __construct()
    {
        parent::__construct();

        $this->action = ActionToolkit::getActionName();
    }

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