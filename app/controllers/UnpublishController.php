<?php
/**
 * Controller to unpublish packages from the repository application.
 *
 * @author  Valentin Duricu <valentin@duricu.ro>
 * @date    21.04.2015
 * @license CC BY-SA 4.0
 */
use traits\VersionCheckTrait;

/**
 * Controller to publish packages into the repository application.
 */
class UnpublishController extends \BaseController
{

    /**
     * Deletes an application from the repository.
     *
     * @throws Exception
     * @return \Illuminate\Http\Response
     */
    public function deleteApplication()
    {
        try {
            DB::beginTransaction();
            $projectName = filter_var(Input::get('name'), FILTER_SANITIZE_STRING);

            $project = Project::where('name', $projectName)
                ->where('user_id', Auth::user()->id)->first();

            if ($project == null)
                throw new \Exception("Only the author can unpublish a project!");

            foreach ($project->versions as $version) {
                $isDependency = $version->isDependency();
                foreach ($isDependency as $dep) {
                    throw new DependencyVersionException($version);
                }
            }

            foreach ($project->versions as $version) {
                $this->removeVersion($version);
            }

            $project->keywords()->detach();

            $project->delete();

            DB::commit();

            return Response::json(["status" => "ok"]);
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Deletes a version of an application from the repository.
     *
     * @throws Exception
     * @return \Illuminate\Http\Response
     */
    public function deleteVersion()
    {
        try {
            DB::beginTransaction();
            $projectName = filter_var(Input::get('name'), FILTER_SANITIZE_STRING);
            $versionId = filter_var(Input::get('version'), FILTER_SANITIZE_STRING);

            $project = Project::where('name', $projectName)
                ->where('user_id', Auth::user()->id)->first();

            if ($project == null)
                throw new \Exception("Only the author can unpublish a project!");

            $version = Version::where('project_id', $project->id)
                ->where('version', $versionId)->firstOrFail();

            $this->removeVersion($version);

            DB::commit();

            return Response::json(["status" => "ok"]);
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Removes a version from the application.
     *
     * @param $version
     *
     * @throws DependencyVersionException
     */
    private function removeVersion($version)
    {
        $isDependency = $version->isDependency();
        foreach ($isDependency as $dep) {
            throw new DependencyVersionException($version);
        }

        $version->dependsOn()->detach();

        $filePath = dirname(__DIR__) . "/../packages/";
        $file = $filePath . $version->filename;

        if (file_exists($file))
            unlink($file);

        $version->delete();
    }

} 