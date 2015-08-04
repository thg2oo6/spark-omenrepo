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

    /**
     * Creates an application version into the repository.
     *
     * @api
     * @throws Exception
     * @return \Illuminate\Http\Response
     */
    public function createApplication()
    {
        try {
            DB::beginTransaction();
            $file = json_decode(Input::get('omenFile'));
            $readme = Input::get('readme');

            if (!Input::hasFile('file'))
                throw new \Exception("You must upload a file!");

            $project = $this->checkProjectExistence($file->name, $file->author);
            if (is_null($project))
                $project = $this->createProject($file);

            $version = $this->checkVersion($project, $file->version);
            $status = "ok";
            if ($version != null)
                throw new ExistingVersionException($version);// $status = "update";
            else
                $version = $this->createVersion($project, $file, $readme);

            if (isset($file->keywords))
                $this->checkKeywords($project, $file->keywords);

            if (isset($file->dependencies))
                $this->checkDependencies($version, $file->dependencies);

            $this->extraInformation($project, $file);

            $project->save();
            DB::commit();

            return Response::json(["status" => $status]);
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Checks the existence of a project for the given author.
     *
     * @param string $name   The name of the project.
     * @param User   $author The autor of the project.
     *
     * @return \Project|null|static
     * @throws Exception
     */
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

    /**
     * Creates a project into the system.
     *
     * @param stdObject $projectFile The project description file.
     *
     * @return Project
     */
    private function createProject($projectFile)
    {
        $project = new Project();
        $project->user_id = Auth::user()->id;
        $project->name = $projectFile->name;

        $this->extraInformation($project, $projectFile);

        $project->save();

        return $project;
    }

    /**
     * Checks and creates new keywords into the repository. Attaches
     * the keywords to the project.
     *
     * @param Project $project  The project for which the keywords are attached.
     * @param array   $keywords A list with keywords.
     */
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

    /**
     * Checks the dependencies of the given version and attaches them to the version.
     *
     * @param Version $version The version of the project to be checked.
     * @param array   $deps    The dependency versions.
     */
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

    /**
     * Validates the current version for the given project.
     *
     * @param Project $project The project for which we update the version.
     * @param string  $version The version to update.
     *
     * @return null|Version
     */
    private function checkVersion($project, $version)
    {
        $versions = $project->versions;
        foreach ($versions as $ver) {
            if ($ver->version == $version) {
                /* Disable the update functionality */
                // $ver->checksum = $this->storeFile($ver->filename);
                // $ver->save();

                return $ver;
            }
        }

        return null;
    }

    /**
     * Creates a new version of the project and stores it in the db.
     *
     * @param Project   $project The project for which the version is being stored.
     * @param stdObject $file    The file containing project and version information.
     * @param string    $readme  The readme file.
     *
     * @return Version
     */
    private function createVersion($project, $file, $readme)
    {
        $version = new Version();
        $version->project_id = $project->id;
        $version->version = $file->version;
        $version->omenFile = json_encode($file);
        $version->filename = sha1($project->id . '|' . $project->name . '|' . $version->version . '|' . mt_rand(1, 10000));
        $version->checksum = $this->storeFile($version->filename);
        $version->readme = $readme;

        $version->save();

        return $version;
    }

    /**
     * Stores the input file with the given hash. Returns the sha1 checksum of the file.
     *
     * @param string $hash The hash for the file to be stored.
     *
     * @return string
     */
    private function storeFile($hash)
    {
        $file = Input::file('file');
        $filePath = dirname(__DIR__) . "/../packages";

        $file->move($filePath, $hash);

        return sha1_file(dirname(__DIR__) . "/../packages/{$hash}");
    }

    /**
     * Extracts the version from a version string.
     *
     * @param string $version The version string.
     *
     * @return array
     */
    private function extractVersion($version)
    {
        preg_match("/^(<|>|<=|>=)?([0-9]+).((\\*|[0-9]+)(\.([0-9]+|\\*))?)$/", $version, $ver);
        $isValid = function (&$e) {
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

    private function extraInformation($project, $projectFile)
    {
        $project->description = null;
        $project->homepage = null;
        $project->license = null;

        if (isset($projectFile->description))
            $project->description = $projectFile->description;

        if (isset($projectFile->homepage))
            $project->homepage = $projectFile->homepage;

        if (isset($projectFile->license))
            $project->license = $projectFile->license;
    }

} 