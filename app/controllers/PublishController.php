<?php
/**
 * Controller to publish packages into the repository application.
 *
 * @author  Valentin Duricu <valentin@duricu.ro>
 * @date    21.04.2015
 * @license CC BY-SA 4.0
 */
use traits\VersionCheckTrait;

/**
 * Controller to publish packages into the repository application.
 */
class PublishController extends \BaseController
{

    use VersionCheckTrait;

    public function createApplication()
    {
        try {
            DB::beginTransaction();
            $file = json_decode(Input::get('omenFile'));
            if (!Input::hasFile('file'))
                throw new \Exception("You must upload a file!");

            $project = $this->checkProjectExistence($file->name, $file->author);
            if (is_null($project))
                $project = $this->createProject($file);

            if (isset($file->keywords))
                $this->checkKeywords($project, $file->keywords);

            $version = $this->checkVersion($project, $file->version);
            $status = "ok";
            if ($version != null)
                $status = "update";
            else
                $version = $this->createVersion($project, $file);

            if (isset($file->dependencies))
                $this->checkDependencies($version, $file->dependencies);
            DB::commit();

            return Response::json(["status" => $status]);
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    private function checkProjectExistence($name, $author)
    {
        $project = Project::where('name', $name)->first();
        if (is_null($project))
            return null;

        if ($project->user->email != $author->email)
            throw new \Exception("You cannot publish a package for another user!");

        if ($project->user->email != Auth::user()->email)
            throw new \Exception("You cannot publish a package for another user!");

        return $project;
    }

    private function createProject($projectFile)
    {
        $project = new Project();
        $project->user_id = Auth::user()->id;
        $project->name = $projectFile->name;

        if (isset($projectFile->description))
            $project->description = $projectFile->description;

        if (isset($projectFile->homepage))
            $project->homepage = $projectFile->homepage;

        if (isset($projectFile->license))
            $project->license = $projectFile->license;

        $project->save();

        return $project;
    }

    private function checkKeywords($project, $keywords)
    {
        $proKeywords = $project->keywords();
        $proKeywords->detach();

        $kArr = [];
        foreach ($keywords as $keyword) {
            $kDb = Keyword::where('name', $keyword)->first();

            if (is_null($kDb)) {
                $kDb = new Keyword();
                $kDb->name = $keyword;
                $kDb->save();
            }

            $kArr[] = $kDb->id;
        }
        $proKeywords->attach($kArr);

        $project->save();
    }

    private function checkDependencies($version, $deps)
    {
        $proDeps = $version->dependsOn();
        $proDeps->detach();

        $depsArr = [];
        foreach ($deps as $project => $verDep) {
            $versionLine = $this->extractVersion($verDep);

            $ver = $this->versionBuilder($project, $versionLine);
            $ver = $ver->firstOrFail();

            $depsArr[] = $ver->id;
        }
        if (count($depsArr) != 0)
            $proDeps->attach($depsArr);

        $version->save();
    }

    private function checkVersion($project, $version)
    {
        $versions = $project->versions;
        foreach ($versions as $ver) {
            if ($ver->version == $version) {
                $ver->checksum = $this->storeFile($ver->filename);
                $ver->save();

                return $ver;
            }
        }

        return null;
    }

    private function createVersion($project, $file)
    {
        $version = new Version();
        $version->project_id = $project->id;
        $version->version = $file->version;
        $version->omenFile = json_encode($file);
        $version->filename = sha1($project->id . '|' . $project->name . '|' . $version->version . '|' . mt_rand(1, 10000));
        $version->checksum = $this->storeFile($version->filename);

        $version->save();

        return $version;
    }

    private function storeFile($hash)
    {
        $file = Input::file('file');
        $filePath = dirname(__DIR__) . "/../packages";

        $file->move($filePath, $hash);

        return sha1_file(dirname(__DIR__) . "/../packages/{$hash}");
    }

    private function extractVersion($version)
    {
        preg_match("/^(<|>|<=|>=)?([0-9]+).((\\*|[0-9]+)(\.([0-9]+|\\*))?)$/", $version, $ver);
        $isValid = function ($e) {
            if (!isset($e)) return false;
            if (empty($e)) return false;

            return true;
        };

        $versLine = [];

        $versLine["operator"] = "=";
        if ($isValid($ver[1]))
            $versLine["operator"] = trim($ver[1]);

        $versLine["major"] = trim($ver[2]);

        $versLine["minor"] = "0";
        if ($isValid($ver[4]))
            $versLine["minor"] = trim($ver[4]);
        if ($versLine["minor"] == "*")
            $versLine["like"] = true;

        $versLine["patch"] = "0";
        if ($isValid($ver[6]))
            $versLine["patch"] = trim($ver[6]);
        if ($versLine["patch"] == "*")
            $versLine["like"] = true;

        return $versLine;
    }

} 