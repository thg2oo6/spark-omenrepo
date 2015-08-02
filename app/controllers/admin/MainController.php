<?php
/**
 *
 *
 * @author  Valentin Duricu <valentin@duricu.ro>
 * @date    28.07.2015
 * @license CC BY-SA 4.0
 */

namespace Admin;

use Keyword;
use Project;
use User;
use View;


/**
 * Class MainController
 *
 * @package Admin
 */
class MainController extends \BaseController
{
    public function getDashboard()
    {
        $packages = Project::orderBy('updated_at', 'desc')->limit(10)->get();
        $packages->load('user', 'keywords', 'versions');

        $keywords = Keyword::all();
        $keywords->load('projects');
        $keywords->sortByDesc(function ($e) {
            $e->projects->count();
        });

        return View::make('admin.index', [
            'packages' => $packages,
            'keywords' => $keywords
        ]);
    }

    public function getKeywords()
    {
        $keywords = Keyword::all();
        $keywords->load('projects');

        return View::make('admin.keywords', [
            'keywords' => $keywords
        ]);
    }

    public function getUsers()
    {
        $users = User::all();
        $users->load('projects');

        return View::make('admin.users', [
            'users' => $users
        ]);
    }

    public function getProjects()
    {
        $packages = Project::orderBy('name')->get();
        $packages->load('user', 'versions');

        return View::make('admin.projects', [
            'projects' => $packages,
        ]);
    }
}