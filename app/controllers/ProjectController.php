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

    /**
     * Downloads the archive for a given project
     *
     * @api
     *
     * @param string $name The name of the project.
     *
     * @return \Illuminate\Http\JsonResponse|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function getDownload($name)
    {
        $project = Project::where('name', $name)->firstOrFail();

        $versions = $project->versions->sortByDesc(function ($e) {
            return $e->version;
        });

        foreach ($versions as $version) {
            $file = dirname(__DIR__) . "/../packages/{$version->filename}";
            if (file_exists($file))
                return Response::download($file);
        }

        return Response::json(["message" => "Unable to find the package"], 500);

    }

}
