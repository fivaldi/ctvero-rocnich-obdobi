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
        $contestsInProgress = Contest::whereRaw('NOW() BETWEEN submission_start AND submission_end')->get();
        foreach ($allContests as $contest) {
            $allContestsDiaries[$contest->name] = DiaryController::listContestDiaries($contest);
        }

        return view('results', [ 'allContests' => $allContests,
                                 'allContestsDiaries' => $allContestsDiaries,
                                 'categories' => $categories,
                                 'contestsInProgress' => $contestsInProgress ]);
    }
}
