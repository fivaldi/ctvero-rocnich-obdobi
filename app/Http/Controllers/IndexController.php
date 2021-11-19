<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Category;
use App\Models\Contest;

class IndexController extends Controller
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
