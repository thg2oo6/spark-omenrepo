<?php
/**
 *
 *
 * @author  Valentin Duricu <valentin@duricu.ro>
 * @date    23.04.2015
 * @license CC BY-SA 4.0
 */

namespace traits;

use Version;


/**
 * Trait VersionCheckTrait
 *
 * @package traits
 */
trait VersionCheckTrait
{

    /**
     * Attaches the version to the query string.
     *
     * @param Builder $versionQuery The query element.
     * @param array   $version      The version array.
     *
     * @return mixed
     */
    protected function buildLikeVersionQuery($versionQuery, $version)
    {
        if ($version['minor'] == "*")
            return $versionQuery->where('version', 'like', $version['major'] . '.%');

        if ($version['patch'] == "*")
            return $versionQuery->where('version', 'like', $version['major'] . '.' . $version['minor'] . '.%');
    }


    protected function versionBuilder($package, $version)
    {
        $verQ = Version::whereHas('project', function ($q) use ($package) {
            $q->where('name', $package);
        });

        if (isset($version['like']) && $version['like'])
            $verQ = $this->buildLikeVersionQuery($verQ, $version);

        if (isset($version['like']) && $version['operator'] != '=') {
            $qVersion = $version['major'];

            if ($version['minor'] == "*")
                $qVersion .= '.0';
            else
                $qVersion .= '.' . $version['minor'];

            $verQ = $verQ->where('version', $version['operator'], $qVersion . '.0');
        }

        if (!isset($version['like'])) {
            $qVersion = $version['major'];
            $qVersion .= '.' . $version['minor'];
            $qVersion .= '.' . $version['patch'];

            $verQ = $verQ->where('version', $version['operator'], $qVersion);
        }

        return $verQ;
    }
} 