<?php

namespace App\Http\Controllers\Admin;

use App\Models\Cycles;
use App\Models\Members;
use App\Models\Question;

class HomeController extends BaseController
{
    /**
     * @var Cycles
     */
    private $cycleModel;

    /**
     * @var Question
     */
    private $questionModel;

    /**
     * @var Members
     */
    private $memberModel;

    /**
     * @param Cycles $cycles
     * @param Question $question
     * @param Members $members
     */
    public function __construct(Cycles $cycles, Question $question, Members $members)
    {
        parent::__construct();

        $this->cycleModel = $cycles;
        $this->questionModel = $question;
        $this->memberModel = $members;
    }
    
    public function index()
    {
        //会员人数
        $memberCount = $this->memberModel->count();
        //达标率超过60%的人数
        $success_rate = $this->memberModel->where('success_rate', '<', 60)->count();
        //期题总数
        $cycles = $this->cycleModel->count();
        //题目总数
        $question = $this->questionModel->count();

        return view(getThemeView('home.index'), [
            'memberCount' => $memberCount, 'success_rate' => $success_rate,
            'cycles' => $cycles, 'question' => $question
        ]);
    }
}
