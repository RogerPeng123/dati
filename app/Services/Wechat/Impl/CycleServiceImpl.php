<?php


namespace App\Services\Wechat\Impl;


use App\Models\Cycles;
use App\Services\Wechat\CycleService;

class CycleServiceImpl implements CycleService
{
    /**
     * @var Cycles
     */
    private $cycleModels;

    public function __construct(Cycles $cycleModels)
    {
        $this->cycleModels = $cycleModels;
    }

    function cycleLists()
    {
        $db = $this->cycleModels
            ->orderBy('years', 'desc')
            ->orderBy('months', 'desc')
            ->orderBy('cycles', 'desc')
            ->simplePaginate(10, ['id', 'title', 'years', 'months', 'cycles'])
            ->items();

        foreach ($db as &$item) {

        }


    }
}