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
        $lastContestDiaries = DiaryController::listContestDiaries($lastContest);
        $categories = Category::allOrdered();
        $mailSuccess = $request->session()->pull('mailSuccess', NULL);
        $mailErrors = $request->session()->pull('mailErrors', NULL);

        return view('index', [ 'lastYearContests' => $lastYearContests,
                               'useScorePoints' => $useScorePoints,
                               'lastContestDiaries' => $lastContestDiaries,
                               'categories' => $categories,
                               'mailSuccess' => $mailSuccess,
                               'mailErrors' => $mailErrors ]);
    }
}
