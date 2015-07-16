<?php
/**
 * Dependency Version Exception
 *
 * @author  Valentin Duricu <valentin@duricu.ro>
 * @date    15.07.2015
 * @license CC BY-SA 4.0
 */

/**
 * Dependency Version Exception triggered when the user tries to
 * unpublish a project version that is a dependency for other projects.
 */
class DependencyVersionException extends \Exception
{
    public function __construct($version)
    {
        parent::__construct("The version you try to unpublish ({$version->version}) is a dependency in other projects!");
    }

} 