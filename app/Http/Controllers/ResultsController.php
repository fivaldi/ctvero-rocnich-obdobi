<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

use App\Models\Contest;
use App\Models\Category;

class ResultsController extends BaseController
{
    public function show()
    {
        $allContests = Contest::allOrdered();
        $categories = Category::allOrdered();
        $contestsInProgress = Contest::submissionActiveOrdered();
        foreach ($allContests as $contest) {
            $diaries = DiaryController::getContestDiaries($contest);
            if ($diaries) {
                $allContestsDiaries[$contest->name] = $diaries;
            }
        }

        return view('results', [ 'title' => 'Výsledkové listiny',
                                 'allContests' => $allContests,
                                 'allContestsDiaries' => $allContestsDiaries,
                                 'categories' => $categories,
                                 'contestsInProgress' => $contestsInProgress ]);
    }
}
