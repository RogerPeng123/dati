<?php


namespace App\Services\Wechat\Impl;


use App\Models\Cycles;
use App\Models\Question;
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

    public function __construct(Cycles $cycleModels, Question $questionModels)
    {
        $this->cycleModels = $cycleModels;
        $this->questionModels = $questionModels;
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

    function cycleSubmit()
    {

    }

}