<?php


namespace App\Services\Wechat\Impl;

use App\Exceptions\ApiAuthenticationException;
use App\Exceptions\ApiResponseExceptions;
use App\Models\Cycles;
use App\Models\IntrgralLog;
use App\Models\Learn;
use App\Models\LearnReadLog;
use App\Models\QuestionAnswer;
use App\Services\Wechat\MemberService;
use App\Toolkit\ActionToolkit;
use App\Toolkit\MemberToolkit;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\Models\Members;
use Illuminate\Support\Facades\Cache;

class MemberServiceImpl implements MemberService
{
    /**
     * @var Members
     */
    private $memberModel;

    /**
     * @var Cycles
     */
    private $cycleModel;

    /**
     * @var QuestionAnswer
     */
    private $questionAnswerModel;

    /**
     * @var Request
     */
    private $request;

    /**
     * @var LearnReadLog
     */
    private $learnReadLog;

    /**
     * @var Learn
     */
    private $learnModel;

    /**
     * @var IntrgralLog
     */
    private $intrgralLog;

    public function __construct(Members $memberModel, Request $request, Cycles $cycles, Learn $learnModel,
                                QuestionAnswer $questionAnswerModel, LearnReadLog $learnReadLog,
                                IntrgralLog $intrgralLog)
    {
        $this->memberModel = $memberModel;
        $this->request = $request;
        $this->cycleModel = $cycles;
        $this->learnModel = $learnModel;
        $this->questionAnswerModel = $questionAnswerModel;
        $this->learnReadLog = $learnReadLog;
        $this->intrgralLog = $intrgralLog;
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
        $this->memberModel->cover = '/cover/cover.jpeg';

        $result = $this->memberModel->save();

        throw_unless($result, ApiResponseExceptions::class, '注册失败');

        unset($this->memberModel->password);
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

        //检查当前用户是否已经领取登录积分
        $checkState = $this->intrgralLog->where([
            'm_id' => $this->memberModel->id, 'type' => $this->intrgralLog::TYPE_LOGIN
        ])->where('created_at', Carbon::today())->exists();

        if (!$checkState) {
            //添加积分记录
            $this->intrgralLog->create([
                'm_id' => $this->memberModel->id,
                'type' => $this->intrgralLog::TYPE_LOGIN,
                'num' => config('integral.login.today_count_num')
            ]);

            $this->memberModel->integral = $this->memberModel->integral + config('integral.login.today_count_num');
        }

        $this->memberModel->save();

        unset($this->memberModel->password);
        unset($this->memberModel->deleted_at);
        unset($this->memberModel->created_at);

        $this->memberModel->cover = env('APP_URL') . $this->memberModel->cover;

        Cache::put('API_TOKEN_MEMBER_' . $this->memberModel->api_token, $this->memberModel, 60 * 24 * 30);

        return $this->memberModel;
    }

    /**
     *
     * Author: roger peng
     * Time: 2019/12/6 23:47
     * @return mixed
     * @throws \Throwable
     */
    function memberInfo()
    {
        if (Cache::has('API_TOKEN_MEMBER_' . $this->request->header('x-api-key')))
            return Cache::get('API_TOKEN_MEMBER_' . $this->request->header('x-api-key'));

        $this->memberModel = $this->memberModel->where('api_token', $this->request->header('x-api-key'))->first();

        unset($this->memberModel->password);
        unset($this->memberModel->deleted_at);
        unset($this->memberModel->created_at);

        throw_unless($this->memberModel,
            ApiAuthenticationException::class, '登录信息失效,请重新登录');

        $this->memberModel->cover = env('APP_URL') . $this->memberModel->cover;

        Cache::put('API_TOKEN_MEMBER_' . $this->request->header('x-api-key'), $this->memberModel, 60 * 24 * 30);

        return $this->memberModel;
    }

    function getMemberAnswerLog()
    {
        $member = Cache::get('API_TOKEN_MEMBER_' . $this->request->header('x-api-key'));

        $data = $this->questionAnswerModel
            ->leftJoin($this->cycleModel->getTable(),
                $this->cycleModel->getTable() . '.' . $this->cycleModel->getKeyName(),
                '=', $this->questionAnswerModel->getTable() . '.qc_id')
            ->where($this->questionAnswerModel->getTable() . '.m_id', $member->id)
            ->orderBy($this->questionAnswerModel->getTable() . '.created_at', 'desc')
            ->paginate(10, [
                $this->questionAnswerModel->getTable() . '.id',
                $this->questionAnswerModel->getTable() . '.success_questions',
                $this->questionAnswerModel->getTable() . '.errors_questions',
                $this->questionAnswerModel->getTable() . '.correct',
                $this->questionAnswerModel->getTable() . '.integral',
                $this->questionAnswerModel->getTable() . '.created_at',
                $this->cycleModel->getTable() . '.title'
            ]);

        return $data->items();
    }

    function getIntegralRank()
    {
        return Cache::remember('MEMBER_INTEGRAL_RANK', 10, function () {

            $data = $this->memberModel->orderBy('integral', 'desc')
                ->paginate(10, ['id', 'nickname', 'cover', 'integral']);

            foreach ($data as &$item) {
                $item->cover = MemberToolkit::conversionCover($item->cover);
            }

            return $data->items();
        });
    }

    //用户学习记录
    function getMemberLearnLog()
    {
        $member = Cache::get('API_TOKEN_MEMBER_' . $this->request->header('x-api-key'));

        return $this->learnReadLog
            ->leftJoin($this->learnModel->getTable(),
                $this->learnModel->getTable() . '.' . $this->learnModel->getKeyName(),
                '=', $this->learnReadLog->getTable() . '.learn_id')
            ->where($this->learnReadLog->getTable() . '.m_id', $member->id)
            ->paginate(10, [
                $this->learnModel->getTable() . '.id',
                $this->learnModel->getTable() . '.title',
                $this->learnReadLog->getTable() . '.created_at'
            ])->items();
    }


}