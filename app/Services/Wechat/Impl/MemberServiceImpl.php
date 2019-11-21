<?php


namespace App\Services\Wechat\Impl;

use App\Exceptions\ApiResponseExceptions;
use App\Services\Wechat\MemberService;
use App\Toolkit\ActionToolkit;
use Illuminate\Support\Facades\Crypt;
use App\Models\Members;
use Illuminate\Support\Facades\Cache;

class MemberServiceImpl implements MemberService
{
    private $memberModel;

    public function __construct(Members $memberModel)
    {
        $this->memberModel = $memberModel;
    }

    /**
     *
     * Author: roger peng
     * Time: 2019/11/20 21:21
     * @param array $param
     * @return Members
     * @throws \Throwable
     */
    function registerMember(array $param)
    {
        if (isset($param['id'])) {
            $this->memberModel = $this->memberModel->find($param['id']);
        }
        $this->memberModel->username = $param['username'];
        $this->memberModel->nickname = $param['nickname'];
        $this->memberModel->password = Crypt::encryptString($param['password']);

        $result = $this->memberModel->save();

        throw_unless($result, ApiResponseExceptions::class, '注册失败');

        return $this->memberModel;
    }

    /**
     *
     * Author: roger peng
     * Time: 2019/11/20 22:03
     * @param array $params
     * @return mixed
     * @throws ApiResponseExceptions
     * @throws \Throwable
     */
    function memberLogin(array $params)
    {
        $this->memberModel = $this->memberModel->where('username', $params['username'])->first();

        throw_unless($this->memberModel, ApiResponseExceptions::class, '没有该账号信息');

        $password = Crypt::decryptString($this->memberModel->password);

        if ($params['password'] != $password) {
            throw new ApiResponseExceptions('密码错误，请重新输入密码');
        }

        $this->memberModel->api_token = ActionToolkit::setApiToken($this->memberModel->id);
        $this->memberModel->save();

        Cache::put('API_TOKEN_MEMBER_' . $this->memberModel->api_token, $this->memberModel);

        return $this->memberModel;
    }


}