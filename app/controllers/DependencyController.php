<?php

/**
 * The dependency manipulation controller.
 *
 * @author  Valentin Duricu <valentin@duricu.ro>
 * @date    20.04.2015
 * @license CC BY-SA 4.0
 */
use Illuminate\Database\Eloquent\ModelNotFoundException;
use traits\VersionCheckTrait;

/**
 * The dependency manipulation controller.
 */
class DependencyController extends \BaseController
{

    use VersionCheckTrait;

    /**
     * Returns the file to be downloaded.
     *
     * @param string $filehash The hash of the file to be downloaded.
     *
     * @return \Illuminate\Http\JsonResponse|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function getDownloadPack($filehash)
    {
        $file = dirname(__DIR__) . "/../packages/{$filehash}";
        if (file_exists($file))
            return Response::download($file);

        return Response::json(["message" => "Unable to find the package"], 500);
    }

    /**
     * Checks the dependencies list sent from the Omen tool.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function postCheckAndBuild()
    {
        $x = Input::all();
        $dependencies = [];
        $errors = [];
        $packs = [];
        $status = "ok";

        foreach ($x['deps'] as $v) {
            $version = $this->versionBuilder($v['package'], $v['version']);

            try {
                $version = $version->firstOrFail();

                if (isset($dependencies[$v['package']]))
                    throw new \Exception("Multiple version for the selected package exists!");

                $dependencies[$v['package']] = URL::to('/api/v1/dependency/download/' . $version->filename);
                $packs[$version->filename] = ["package" => $v['package'], "version" => $version->version];

                $this->recursiveDeps($dependencies, $packs, $version);
            } catch (ModelNotFoundException $ex) {
                $status = "error";
                $version = Version::whereHas('project', function ($q) use ($v) {
                    $q->where('name', $v['package']);
                })->get();

                $versions = [];
                foreach ($version as $vers) {
                    $versions[] = $vers->version;
                }

                $errors[$v['package']] = count($versions) == 0 ? ["message" => "Selected package doesn't exists!"] : ["message" => "Selected version doesn't exists!", "available" => $versions];
            } catch (\Exception $ex) {
                $status = "error";
                $errors[$v['package']] = ["message" => $ex->getMessage()];
            }
        }

        return Response::json(["status" => $status, "dependencies" => $dependencies, "packages" => $packs, "errors" => $errors]);
    }

    /**
     * Checks the updated dependencies list sent from the Omen tool.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function postCheckUpdateAndBuild()
    {
        $x = Input::all();
        $dependencies = [];
        $errors = [];
        $packs = [];
        $status = "ok";

        foreach ($x['deps'] as $v) {
            $version = $this->versionBuilder($v['package'], $v['version']);

            try {
                $version = $version->firstOrFail();

                if (isset($x['installed'][$v['package']]))
                    if ($x['installed'][$v['package']] == $version->version)
                        continue;

                if (isset($dependencies[$v['package']]))
                    throw new \Exception("Multiple version for the selected package exists!");

                $dependencies[$v['package']] = URL::to('/api/v1/dependency/download/' . $version->filename);
                $packs[$version->filename] = ["package" => $v['package'], "version" => $version->version];

                $this->recursiveDeps($dependencies, $packs, $version);
            } catch (ModelNotFoundException $ex) {
                $status = "error";
                $version = Version::whereHas('project', function ($q) use ($v) {
                    $q->where('name', $v['package']);
                })->get();

                $versions = [];
                foreach ($version as $vers) {
                    $versions[] = $vers->version;
                }

                $errors[$v['package']] = count($versions) == 0 ? ["message" => "Selected package doesn't exists!"] : ["message" => "Selected version doesn't exists!", "available" => $versions];
            } catch (\Exception $ex) {
                $status = "error";
                $errors[$v['package']] = ["message" => $ex->getMessage()];
            }
        }

        return Response::json(["status" => $status, "dependencies" => $dependencies, "packages" => $packs, "errors" => $errors]);
    }

    private function recursiveDeps(&$dependencies, &$packs, $version)
    {
        if (is_null($version))
            return;

        $depList = $version->dependsOn;
        foreach ($depList as $deps) {
            $proname = $deps->project->name;
            if (!isset($dependencies[$proname])) {
                $dependencies[$proname] = URL::to('/api/v1/dependency/download/' . $deps->filename);
                $packs[$deps->filename] = ["package" => $proname, "version" => $deps->version];
            }

            $this->recursiveDeps($dependencies, $packs, $deps);
        }
    }


}
