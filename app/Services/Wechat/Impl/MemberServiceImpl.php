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
use App\Toolkit\TimeToolkit;
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

    private $members;

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

        $this->members = Cache::get('API_TOKEN_MEMBER_' . $this->request->header('x-api-key'));
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
        $this->memberModel->type = $param['type'];

        $result = $this->memberModel->save();

        throw_unless($result, ApiResponseExceptions::class, '注册失败');

        //注册成功之后，清理总人数缓存
        Cache::forget('COUNT_MEMBER_NUM');

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
        ])->whereBetween('created_at', TimeToolkit::getDayStarAndEnd())->exists();

        if (!$checkState) {
            //添加积分记录
            $this->intrgralLog->insert([
                'm_id' => $this->memberModel->id,
                'type' => $this->intrgralLog::TYPE_LOGIN,
                'num' => config('integral.login.today_count_num'),
                'created_at' => date('Y-m-d H:i:s', time()),
                'updated_at' => date('Y-m-d H:i:s', time()),
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
        $this->memberModel = Cache::get('API_TOKEN_MEMBER_' . $this->request->header('x-api-key'));

        //题库待完成数据
        $this->memberModel->pending = $this->pending($this->memberModel->id);

        //题库完成率计算
        $this->memberModel->completion = $this->completion($this->memberModel->pending);

        Cache::put('API_TOKEN_MEMBER_' . $this->request->header('x-api-key'), $this->memberModel, 60 * 24 * 30);

        return $this->memberModel;
    }

    //计算出完成率
    private function completion(int $pendint): float
    {
        $count = $this->cycleModel->where('status', $this->cycleModel::SHOW_STATUS)->count();

        return round(($count - $pendint) / $count * 100, 2);
    }

    //计算当前用户在题库中没有作答的题目数量
    private function pending(int $mId): int
    {
        $cycles = $this->cycleModel->where('status', $this->cycleModel::SHOW_STATUS)->get(['id']);

        $num = 0;
        foreach ($cycles as $cycle) {
            $exists = $this->existesCycle($cycle->id, $mId);
            if ($exists) {
                $num++;
            }
        }

        return $num;
    }

    //判断当前用户对于答题是否作答
    private function existesCycle($qcId, $mId): int
    {
        return Cache::remember('QUESTION_ANSWER_EXISTS_QC_ID_' . $qcId . '_MID_' . $mId, 60 * 24 * 30,
            function () use ($qcId, $mId) {
                if ($this->questionAnswerModel->where(['qc_id' => $qcId, 'm_id' => $mId])->exists()) {
                    return 0;
                } else {
                    return 1;
                }
            });
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
            ->orderBy($this->learnReadLog->getTable() . '.created_at', 'desc')
            ->paginate(10, [
                $this->learnModel->getTable() . '.id',
                $this->learnModel->getTable() . '.title',
                $this->learnReadLog->getTable() . '.created_at'
            ])->items();
    }

    function getMemberIntegralLogs()
    {
        $data = $this->intrgralLog->where('m_id', $this->members->id)->orderBy('created_at', 'desc')
            ->paginate(10, [
                'id', 'type', 'num', 'created_at'
            ])->items();

        foreach ($data as &$item) {
            switch ($item->type) {
                case $this->intrgralLog::TYPE_LOGIN:
                    $item->type = '登录积分';
                    break;
                case $this->intrgralLog::TYPE_READ:
                    $item->type = '学习积分';
                    break;
                case $this->intrgralLog::TYPE_QUESTION_BANK:
                    $item->type = '自测积分';
                    break;
                case $this->intrgralLog::TYPE_LEAEN:
                    $item->type = '题库学习';
                    break;
                case $this->intrgralLog::TYPE_COLLECTION:
                    $item->type = '收藏积分';
                    break;
                default:
                    $item->type = '未知异常数据';
            }
        }

        return $data;
    }

    function changeMemberInfo(array $update): Members
    {
        $this->memberModel = $this->memberModel->find($this->members->id);

        $vaule = current($update);
        $key = key($update);

        $this->memberModel->$key = $vaule;

        $result = $this->memberModel->save();

        throw_unless($result, ApiResponseExceptions::class, '修改信息失败');

        Cache::forget('API_TOKEN_MEMBER_' . $this->members->api_token);

        unset($this->memberModel->password);
        unset($this->memberModel->deleted_at);
        unset($this->memberModel->created_at);

        return $this->memberModel;
    }


}