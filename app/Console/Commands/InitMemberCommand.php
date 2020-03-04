<?php

namespace App\Console\Commands;

use App\Models\Cycles;
use App\Models\Members;
use App\Models\QuestionAnswer;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class InitMemberCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'member:init';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '初始化所有的用户数据';

    private $member;

    private $cycleModels;

    private $questionAnswerModels;

    /**
     * Create a new command instance.
     *
     * @param Members $member
     * @param Cycles $cycleModels
     * @param QuestionAnswer $questionAnswerModels
     *
     * @return void
     */
    public function __construct(Members $member, Cycles $cycleModels, QuestionAnswer $questionAnswerModels)
    {
        parent::__construct();

        $this->member = $member;
        $this->cycleModels = $cycleModels;
        $this->questionAnswerModels = $questionAnswerModels;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $members = $this->member->get(['id', 'api_token']);
        foreach ($members as $member) {
            //计算用户的达标率
            $success_rate = $this->successRate($member->id);

            $int = $this->member->where(['id' => $member->id])->update(['success_rate' => $success_rate]);
            if ($int || $member->api_token) {
                Cache::forget('API_TOKEN_MEMBER_' . $member->api_token);
            }
        }

        $this->output->success('处理完成用户达标率');
    }

    //达标率
    private function successRate(int $mId): float
    {
        $cycles = $this->cycleModels->where(['status' => $this->cycleModels::SHOW_STATUS])->get(['id']);

        $count = 0;
        $corrects = 0;

        foreach ($cycles as $cycle) {
            $exists = $this->existesCycle($cycle->id, $mId);
            if (!$exists) {
                $count++;

                $corrects += $this->correctCount($cycle->id, $mId);
            }
        }

        if ($corrects != 0) {
            return round($corrects / $count, 2);
        }

        return 0;
    }

    //判断当前用户对于答题是否作答
    private function existesCycle($qcId, $mId): int
    {
        return Cache::remember('QUESTION_ANSWER_EXISTS_QC_ID_' . $qcId . '_MID_' . $mId, 60 * 24 * 30,
            function () use ($qcId, $mId) {
                if ($this->questionAnswerModels->where(['qc_id' => $qcId, 'm_id' => $mId])->exists()) {
                    return 0;
                } else {
                    return 1;
                }
            });
    }

    private function correctCount(int $id, int $mId): float
    {
        return Cache::remember('QUESTION_ANSWER_CORRECT_' . $id, 60 * 24 * 30, function () use ($id, $mId) {
            $answer = $this->questionAnswerModels->where(['qc_id' => $id, 'm_id' => $mId])
                ->orderBy('id', 'desc')->first(['correct']);

            return $answer['correct'];
        });
    }
}
