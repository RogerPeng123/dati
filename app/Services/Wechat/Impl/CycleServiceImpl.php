<?php


namespace App\Services\Wechat\Impl;

use App\Exceptions\ApiResponseExceptions;
use App\Models\Cycles;
use App\Models\MemberIntegralLog;
use App\Models\Members;
use App\Models\Question;
use App\Models\QuestionAnswer;
use App\Models\QuestionOptions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Services\Wechat\CycleService;
use Illuminate\Support\Facades\DB;
use Psr\Log\LoggerInterface;

class CycleServiceImpl implements CycleService
{
    /**
     * @var Cycles
     */
    private $cycleModels;

    /**
     * @var Question
     */
    private $questionModels;

    /**
     * @var QuestionOptions
     */
    private $questionOptionsModels;

    /**
     * @var QuestionAnswer
     */
    private $questionAnswerModels;

    /**
     * @var Members
     */
    private $memberModel;

    /**
     * @var MemberIntegralLog
     */
    private $memberIntegralLog;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var Request
     */
    private $request;

    public function __construct(Cycles $cycleModels, Question $questionModels, LoggerInterface $logger,
                                QuestionOptions $questionOptionsModels, QuestionAnswer $questionAnswerModels,
                                Members $memberModel, MemberIntegralLog $integralLog, Request $request)
    {
        $this->cycleModels = $cycleModels;
        $this->questionModels = $questionModels;
        $this->questionOptionsModels = $questionOptionsModels;
        $this->questionAnswerModels = $questionAnswerModels;
        $this->memberModel = $memberModel;
        $this->memberIntegralLog = $integralLog;
        $this->logger = $logger;
        $this->request = $request;
    }

    function cycleLists($user)
    {
        $db = $this->cycleModels
            ->where('status', $this->cycleModels::SHOW_STATUS)
            ->orderBy('years', 'desc')
            ->orderBy('months', 'desc')
            ->orderBy('cycles', 'desc')
            ->simplePaginate(10, ['id', 'title', 'years', 'months', 'cycles']);

        foreach ($db as $item) {
            if ($this->questionAnswerModels->where(['qc_id' => $item->id, 'm_id' => $user->id])->exists()) {
                $item->is_answer = 1;
            } else {
                $item->is_answer = 0;
            }
        }

        return $db->items();
    }

    function cycleQuestion(int $id)
    {
        $db = $this->questionModels->where('qc_id', $id)->get(['id', 'title', 'type', 'judge_success']);

        foreach ($db as &$item) {
            $item['question_options'] = $item->questionOptions;
        }

        return $db;
    }

    function cycleQuestionNext()
    {
        $member = Cache::get('API_TOKEN_MEMBER_' . $this->request->header('x-api-key'));

        $qcArray = $this->questionAnswerModels->where(['m_id' => $member->id])->groupBy('qc_id')->pluck('qc_id');

        $id = $this->cycleModels->whereNotIn('id', $qcArray)->orderBy('id', 'desc')->value('id');

        return ['qc_id' => $id, 'questions' => $this->cycleQuestion($id)];
    }


    /**
     *
     * Author: roger peng
     * Time: 2019/11/23 14:08
     * @param array $params
     * @return array
     * @throws ApiResponseExceptions
     * @throws \Throwable
     */
    function cycleSubmit(array $params)
    {
        //解析json数据
        $body = json_decode($params['body'], true);

        $cycles = $this->cycleModels->find($params['qc_id']);
        if (count($body) != $cycles->num) {
            throw new ApiResponseExceptions('答题数目，与该期题目数目不符合');
        }

        //答对题目数
        $questionsSuccessNum = 0;

        //答对题目数据
        $questionsSuccessArray = [];

        foreach ($body as $item) {
            $questions = $this->questionModels->find($item['q_id']);
            switch ($questions->type) {
                case $this->questionModels::TYPE_JUDGE:
                    if ($questions->judge_success == $item['answer']) {
                        ++$questionsSuccessNum;
                        $questionsSuccessArray[]['q_o_id'] = $item['q_id'];
                    }
                    break;
                case $this->questionModels::TYPE_CHOOSE:
                    $questionOption = $this->questionOptionsModels->where([
                        'q_id' => $item['q_id'], 'is_success' => $this->questionOptionsModels::SUCCESS_OPTIONS
                    ])->first(['id']);

                    if ($item['answer'] == $questionOption->id) {
                        ++$questionsSuccessNum;
                        $questionsSuccessArray[]['q_o_id'] = $item['q_id'];
                    }
                    break;
                case $this->questionModels::TYPE_MULTI:
                    $questionOption = $this->questionOptionsModels->where([
                        'q_id' => $item['q_id'], 'is_success' => $this->questionOptionsModels::SUCCESS_OPTIONS])
                        ->groupBy('id')->pluck('id');

                    //判断两个数组的交集，如果为空数组，那么就证明相同
                    $questionDiff = array_diff($questionOption->toArray(), $item['answer']);
                    if (empty($questionDiff)) {
                        ++$questionsSuccessNum;
                        $questionsSuccessArray[]['q_o_id'] = $item['q_id'];
                    }
                    break;
                default:
                    throw new ApiResponseExceptions('错误题目参数: ' . $item['q_id']);
            }

        }

        $result = [
            'successQuestions' => $questionsSuccessNum,
            'errorsQuestions' => $cycles->num - $questionsSuccessNum,
            'correct' => round($questionsSuccessNum / $cycles->num * 100, 2),
            'integral' => config('integral.num') * $questionsSuccessNum
        ];

        $member = Cache::get('API_TOKEN_MEMBER_' . $this->request->header('x-api-key'));

        DB::beginTransaction();

        try {

            $this->questionAnswerModels->qc_id = $params['qc_id'];
            $this->questionAnswerModels->m_id = $member->id;
            $this->questionAnswerModels->success_questions = $result['successQuestions'];
            $this->questionAnswerModels->errors_questions = $result['errorsQuestions'];
            $this->questionAnswerModels->correct = $result['correct'];

            if (!$this->questionAnswerModels->save())
                throw new ApiResponseExceptions('答题失败');

            if ($result['integral'] > 0) {
                //答题成功后执行新增积分操作
                $res = $this->memberModel->where('id', $member->id)->increment('integral', $result['integral']);

                if (!$res)
                    throw new ApiResponseExceptions('积分添加失败');

                //插入多条正确答案记录(作为积分记录)
                foreach ($questionsSuccessArray as $key => $questionSuccessItem) {
                    $questionsSuccessArray[$key]['m_id'] = $member->id;
                    $questionsSuccessArray[$key]['q_a_id'] = $this->questionAnswerModels->id;

                    $questionsSuccessArray[$key]['created_at'] = date('Y-m-d H:i:s', time());
                    $questionsSuccessArray[$key]['updated_at'] = date('Y-m-d H:i:s', time());
                }

                $this->memberIntegralLog->insert($questionsSuccessArray);
            }

            $this->memberModel->where('id', $member->id)->increment('questions_num');

            DB::commit();

            //清理缓存
            Cache::forget('API_TOKEN_MEMBER_' . $this->request->header('x-api-key'));
        } catch (ApiResponseExceptions $exception) {
            DB::rollBack();
            throw new ApiResponseExceptions($exception->getMessage());
        }

        $this->logger->info('答题反馈', $result);

        return $result;
    }

}