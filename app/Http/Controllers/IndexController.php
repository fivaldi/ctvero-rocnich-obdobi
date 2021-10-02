<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;

use App\Models\Contest;
use App\Models\Category;

class IndexController extends BaseController
{
    public function show(Request $request)
    {
        $lastContest = Contest::lastContest();
        $useScorePoints = ($lastContest->options['criterion'] ?? NULL == 'score_points') or false;
        $lastYearContests = Contest::lastYear();
        $lastContestDiaries = DiaryController::getContestDiaries($lastContest);
        $categories = Category::allOrdered();

        return view('index', [ 'title' => 'Home',
                               'lastYearContests' => $lastYearContests,
                               'useScorePoints' => $useScorePoints,
                               'lastContestDiaries' => $lastContestDiaries,
                               'categories' => $categories,
                               'lastContest' => $lastContest ]);
    }
}
