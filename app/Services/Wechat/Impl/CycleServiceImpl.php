<?php


namespace App\Services\Wechat\Impl;


use App\Exceptions\ApiResponseExceptions;
use App\Models\Cycles;
use App\Models\Question;
use App\Models\QuestionAnswer;
use App\Models\QuestionOptions;
use Illuminate\Support\Facades\Cache;
use App\Services\Wechat\CycleService;

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

    public function __construct(Cycles $cycleModels, Question $questionModels,
                                QuestionOptions $questionOptionsModels, QuestionAnswer $questionAnswerModels)
    {
        $this->cycleModels = $cycleModels;
        $this->questionModels = $questionModels;
        $this->questionOptionsModels = $questionOptionsModels;
        $this->questionAnswerModels = $questionAnswerModels;
    }

    function cycleLists()
    {
        $db = $this->cycleModels
            ->orderBy('years', 'desc')
            ->orderBy('months', 'desc')
            ->orderBy('cycles', 'desc')
            ->simplePaginate(10, ['id', 'title', 'years', 'months', 'cycles']);

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

    /**
     *
     * Author: roger peng
     * Time: 2019/11/23 14:08
     * @param array $params
     * @param string $token
     * @return array
     * @throws ApiResponseExceptions
     * @throws \Throwable
     */
    function cycleSubmit(array $params, string $token)
    {
        //解析json数据
        $body = json_decode($params['body'], true);

        $cycles = $this->cycleModels->find($params['qc_id']);
        if (count($body) != $cycles->num) {
            throw new ApiResponseExceptions('答题数目，与该期题目数目不符合');
        }

        //答对题目数
        $questionsSuccessNum = 0;

        foreach ($body as $item) {
            $questions = $this->questionModels->find($item['q_id']);
            if ($questions->type == $this->questionModels::TYPE_JUDGE) {
                if ($questions->judge_success == $item['answer']) {
                    ++$questionsSuccessNum;
                }
            } elseif ($questions->type == $this->questionModels::TYPE_CHOOSE) {

                $questionOption = $this->questionOptionsModels->where([
                    'q_id' => $item['q_id'], 'is_success' => $this->questionOptionsModels::SUCCESS_OPTIONS
                ])->first(['id']);

                if ($item['answer'] == $questionOption->id) {
                    ++$questionsSuccessNum;
                }
            } else {
                throw new ApiResponseExceptions('错误题目参数: ' . $item['q_id']);
            }
        }

        $result = [
            'successQuestions' => $questionsSuccessNum,
            'errorsQuestions' => $cycles->num - $questionsSuccessNum,
            'correct' => round($questionsSuccessNum / $cycles->num * 100, 2)
        ];

        $member = Cache::get('API_TOKEN_MEMBER_' . $token);

        $this->questionAnswerModels->qc_id = $params['qc_id'];
        $this->questionAnswerModels->m_id = $member->id;
        $this->questionAnswerModels->success_questions = $result['successQuestions'];
        $this->questionAnswerModels->errors_questions = $result['errorsQuestions'];
        $this->questionAnswerModels->correct = $result['correct'];

        throw_unless($this->questionAnswerModels->save(), ApiResponseExceptions::class, '答题失败');

        return $result;
    }

}