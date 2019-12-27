<?php


namespace App\Services\Wechat\Impl;


use App\Exceptions\ApiResponseExceptions;
use App\Models\IntrgralLog;
use App\Models\Learn;
use App\Models\LearnReadLog;
use App\Models\Members;
use App\Services\Wechat\LearnService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class LearnServiceImpl implements LearnService
{
    /**
     * @var Request
     */
    private $request;

    /**
     * @var LearnReadLog
     */
    private $learnReadLog;

    /**
     * @var IntrgralLog
     */
    private $intrgralLog;

    /**
     * @var Members
     */
    private $membersModel;

    /**
     * @var Learn
     */
    private $learnModel;

    public function __construct(Request $request, LearnReadLog $learnReadLog, IntrgralLog $intrgralLog,
                                Members $membersModel, Learn $learnModel)
    {
        $this->request = $request;
        $this->learnReadLog = $learnReadLog;
        $this->intrgralLog = $intrgralLog;
        $this->membersModel = $membersModel;
        $this->learnModel = $learnModel;
    }

    function getLearnLists()
    {
        return $this->learnModel->simplePaginate(10, ['id', 'title', 'abstract'])->items();
    }

    /**
     *
     * Author: roger peng
     * Time: 2019/12/26 22:15
     * @param int $id
     * @return mixed
     * @throws ApiResponseExceptions
     * @throws \Throwable
     */
    function findLearn(int $id)
    {
        $result = $this->learnModel->where('id', $id)->first(['id', 'title', 'abstract', 'content']);

        throw_unless($result, ApiResponseExceptions::class, '没有知识点数据');

        $this->learnIntegralHandle($id);

        return $result;
    }

    /**
     * 阅读知识点的积分操作
     * Author: roger peng
     * Time: 2019/12/26 22:38
     * @param int $id
     * @throws ApiResponseExceptions
     */
    private function learnIntegralHandle(int $id)
    {
        $member = Cache::get('API_TOKEN_MEMBER_' . $this->request->header('x-api-key'));

        //查看当前用户今日阅读记录数量
        $count = $this->learnReadLog->where(['m_id' => $member->id])->where('created_at', Carbon::today())->count();

        DB::beginTransaction();
        try {
            //添加阅读文章记录
            $this->learnReadLog->m_id = $member->id;
            $this->learnReadLog->learn_id = $id;

            $this->learnReadLog->save();

            //增加学习次数
            $this->membersModel->where('id', $member->id)->increment('learn_num');

            //次数*单次可获得的积分 如果小于每日可获得的积分
            if ($count * config('integral.learn.today_read') < config('integral.learn.today_read_num')) {

                //当前用户是否阅读过该文章
                $exists = $this->learnReadLog->where(['m_id' => $member->id, 'learn_id' => $id])->exists();

                if (!$exists) {
                    //添加积分记录
                    $this->intrgralLog->m_id = $member->id;
                    $this->intrgralLog->type = $this->intrgralLog::TYPE_READ;
                    $this->intrgralLog->num = config('integral.learn.today_read');

                    $this->intrgralLog->save();

                    //用户添加积分
                    $this->membersModel->where('id', $member->id)
                        ->increment('integral', config('integral.learn.today_read'));
                }
            }

            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();

            throw new ApiResponseExceptions('查看文章信息失败,积分记录异常');
        }
    }


}