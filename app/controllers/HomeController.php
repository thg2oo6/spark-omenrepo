<?php
/**
 * The controller for the main actions
 *
 * @author  Valentin Duricu <valentin@duricu.ro>
 * @date    12.07.2015
 * @license CC BY-SA 4.0
 */

/**
 * The controller for the main actions
 */
class HomeController extends \BaseController
{
    /**
     * Returns the main page.
     *
     * @return \View
     */
    public function getMain()
    {
        $versions = Version::orderBy('updated_at', 'desc')->limit(10)->get();
        $versions->load('project.user', 'project.keywords');

        return View::make('index', [
            "versions" => $versions
        ]);
    }

    /**
     * Performs a search after the given package name or keyword name.
     *
     * @return \Illuminate\Http\RedirectResponse|\View
     */
    public function getSearch()
    {
        if (!Input::has('package'))
            return Redirect::to('/');

        $package = filter_var(Input::get('package'), FILTER_SANITIZE_STRING);
        if (empty($package))
            return Redirect::to('/');

        $projects = Project::where('name', $package)
            ->orwhereHas('keywords', function ($q) use ($package) {
                $q->where('name', $package);
            })->get();

        $projects->load('keywords', 'user');

        return View::make('search', [
            "searchString" => $package,
            "projects"     => $projects
        ]);
    }

}