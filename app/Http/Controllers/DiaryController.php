<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Diary;

class DiaryController extends Controller
{
    public static function getContestDiaries($contest)
    {
        $categories = Category::all();
        if (isset($contest->options['criterion'])) {
            $sortByDesc = $contest->options['criterion'];
        } else {
            $sortByDesc = 'qso_count';
        }
        $diaries = Diary::with('category')->ofContest($contest->id)->sortByDesc($sortByDesc)->get();
        $diariesInCategories = array();

        foreach ($categories as $category) {
            foreach ($diaries as $diary) {
                if ($diary->category_id == $category->id) {
                    $diariesInCategories[$category->id][$diary->id] = $diary;
                }
            }
        }

        return $diariesInCategories;
    }
}
