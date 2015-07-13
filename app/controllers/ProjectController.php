<?php

/**
 * The project manipulation controller.
 *
 * @author  Valentin Duricu <valentin@duricu.ro>
 * @date    19.04.2015
 * @license CC BY-SA 4.0
 */

/**
 * The project manipulation controller.
 */
class ProjectController extends \BaseController
{

    /**
     * Displays all the information that a project holds.
     *
     * @param string $name The name of the project.
     *
     * @return \Illuminate\Http\RedirectResponse|\View
     */
    public function getProject($name)
    {
        $name = filter_var($name, FILTER_SANITIZE_STRING);
        if (empty($name))
            return Redirect::to('/');

        $project = Project::where('name', $name)->firstOrFail();
        $project->load('versions', 'keywords', 'user');

        return View::make('project', [
            "project" => $project
        ]);
    }

}
