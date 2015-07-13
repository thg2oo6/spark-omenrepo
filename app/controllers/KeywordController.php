<?php

/**
 * The keyword manipulation controller.
 *
 * @author  Valentin Duricu <valentin@duricu.ro>
 * @date    19.04.2015
 * @license CC BY-SA 4.0
 */

/**
 * The keyword manipulation controller.
 */
class KeywordController extends \BaseController
{

    /**
     * Searches for the given keyword.
     *
     * @param string $keyword The keyword to search after.
     *
     * @return \Illuminate\Http\RedirectResponse|\View
     */
    public function getKeywordSearch($keyword)
    {
        $keyword = filter_var($keyword, FILTER_SANITIZE_STRING);
        if (empty($keyword))
            return Redirect::to('/');

        $projects = Project::whereHas('keywords', function ($q) use ($keyword) {
            $q->where('name', $keyword);
        })->get();

        $projects->load('keywords', 'user');

        return View::make('search', [
            "searchString" => $keyword,
            "projects"     => $projects
        ]);
    }

}
