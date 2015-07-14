<?php
/**
 * Existing Version Exception
 *
 * @author  Valentin Duricu <valentin@duricu.ro>
 * @date    14.07.2015
 * @license CC BY-SA 4.0
 */

/**
 * Existing Version Exception triggered when the user tries to
 * publish a project version that already exists.
 */
class ExistingVersionException extends \Exception
{
    public function __construct($version)
    {
        parent::__construct("The version you try to publish ({$version->version}) already exists!");
    }

} 