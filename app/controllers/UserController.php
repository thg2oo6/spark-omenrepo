<?php

/**
 * The user manipulation controller.
 *
 * @author  Valentin Duricu <valentin@duricu.ro>
 * @date    19.04.2015
 * @license CC BY-SA 4.0
 */

/**
 * The user manipulation controller.
 */
class UserController extends \BaseController
{

    /**
     * Does the user login.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postLogin()
    {
        $user = [
            'username' => Input::get('username'),
            'password' => Input::get('password'),
            'isActive' => 1
        ];

        if (Auth::attempt($user)) {
            return Redirect::intended('/profile/' . $user['username']);
        }

        return Redirect::to('/login')
            ->with('omen_error', 'The user or the password aren\'t valid!')
            ->withInput();
    }

    /**
     * Does the user login (API).
     *
     * @api
     * @return \Illuminate\Http\JsonResponse
     */
    public function apiLogin()
    {
        $user = [
            'username' => Input::get('username'),
            'email'    => Input::get('email'),
            'password' => Input::get('password'),
            'isActive' => 1
        ];

        if (Auth::attempt($user)) {
            $token = new Token();
            $token->user_id = Auth::user()->id;
            $token->save();

            $name = Auth::user()->firstname . ' ' . Auth::user()->lastname;

            return Response::json(["status" => "ok", "token" => $token->uuid, "name" => $name]);
        }

        /* TODO: create user if not found. */

        return Response::json(["status" => "error", "message" => "Invalid user, email or password"], 403);
    }

    /**
     * Returns the profile page for a given user.
     *
     * @param string $username The name of the user
     *
     * @return \View
     */
    public function getProfile($username)
    {
        $user = User::where('username', $username)->firstOrFail();
        $user->load('projects', 'projects.versions');

        return View::make('profile', [
            "user"     => $user,
            "projects" => $user->projects->sortByDesc('updated_at')
        ]);

    }

    /**
     * Does the user registration.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postCreateNewAccount()
    {
        $user = [];
        try {
            $response = $this->createUserAccount($user);

            return Redirect::to('login')
                ->with('omen_notice', $response);

        } catch (\Exception $ex) {
            return Redirect::to('signup')
                ->with('omen_error', $ex->getMessage())
                ->withInput($user);
        }
    }

    /**
     * Creates a new user account.
     *
     * @param array $user Output array for error handling purpose.
     *
     * @return string
     * @throws Exception
     */
    private function createUserAccount(&$user)
    {
        $user = [
            'username'  => Input::get('username'),
            'password'  => Input::get('password'),
            'cpassword' => Input::get('cpassword'),
            'email'     => Input::get('email'),
            'firstname' => Input::get('firstname'),
            'lastname'  => Input::get('lastname'),
            'isActive'  => !intval(\Config::get('omen.activation'))
        ];

        foreach ($user as $k => $v) {
            if ($k != 'isActive' && $k != 'email')
                $user[$k] = filter_var($v, FILTER_SANITIZE_STRING);
        }

        $user['email'] = filter_var($user['email'], FILTER_SANITIZE_EMAIL);

        $this->passwordCheck($user);
        $this->userCheck($user);

        if (\Config::get('omen.activation')) {
            $user['activationCode'] = md5($user['username'] . '|' . $user['firstname'] . $user['lastname'] . '|' . mt_rand());
        }

        $userDb = new User();
        $userDb->username = $user['username'];
        $userDb->password = $user['password'];
        $userDb->firstname = $user['firstname'];
        $userDb->lastname = $user['lastname'];
        $userDb->email = $user['email'];
        $userDb->isActive = $user['isActive'];
        $userDb->activationCode = isset($user['activationCode']) ? $user['activationCode'] : null;
        $userDb->save();

        if (\Config::get('omen.activation')) {
            Mail::send('mail.activation', [
                "user" => $userDb
            ], function ($message) use ($userDb) {
                $message->to($userDb->email, $userDb->firstname . ' ' . $userDb->lastname)->subject('Omen repository account activation');
            });

            return 'Please activate your account, then login into it!';
        }

        return 'You can now login into your account!';
    }

    /**
     * Activates the user with the given key.
     *
     * @param string $key The key to be activated
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getActivateAccount($key)
    {
        $user = User::where('activationCode', $key)->firstOrFail();
        $user->isActive = 1;
        $user->activationCode = null;
        $user->save();

        return Redirect::to('login')
            ->with('omen_notice', 'You can now login into your account!');
    }

    /**
     * Resets the password for the given user.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postForgotPassword()
    {
        $username = filter_var(Input::get('username'), FILTER_SANITIZE_STRING);
        $passwd = Str::random(8);

        $user = User::where('username', $username)->firstOrFail();
        $user->password = $passwd;
        $user->save();

        Mail::send('mail.resetPassword', [
            "user"     => $user,
            "password" => $passwd
        ], function ($message) use ($user) {
            $message->to($user->email, $user->firstname . ' ' . $user->lastname)->subject('Omen repository password change');

        });

        return Redirect::to('/login')
            ->with('omen_notice', 'Your password has been reset!');
    }

    /**
     * Displays the account modification page.
     *
     * @return \View
     */
    public function getAccount()
    {
        return View::make('account', [
            "user" => Auth::user()
        ]);
    }

    /**
     * Modifies the account information (user information/password)
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postAccount()
    {
        $user = [
            'username'    => Auth::user()->username,
            'password'    => Input::get('password'),
            'cpassword'   => Input::get('cpassword'),
            'email'       => Input::get('email'),
            'firstname'   => Input::get('firstname'),
            'lastname'    => Input::get('lastname'),
            'oldpassword' => Input::get('oldpassword')
        ];

        $section = filter_var(Input::get('section'), FILTER_SANITIZE_STRING);

        foreach ($user as $k => $v) {
            if ($k != 'email')
                $user[$k] = filter_var($v, FILTER_SANITIZE_STRING);
        }

        $user['email'] = filter_var($user['email'], FILTER_SANITIZE_EMAIL);

        try {
            if ($section == 'password')
                $this->passwordCheck($user);
            else
                $this->userCheck($user);

        } catch (\Exception $ex) {
            return Redirect::to('account')
                ->with('omen_error', $ex->getMessage())
                ->withInput($user);
        }

        $userDb = Auth::user();
        if ($section == 'password') {
            $userDb->password = $user['password'];
        } else {
            $userDb->firstname = $user['firstname'];
            $userDb->lastname = $user['lastname'];
            $userDb->email = $user['email'];
        }
        $userDb->save();

        return Redirect::to('account');

    }

    /**
     * Does the checks for password.
     *
     * @param array $user User information.
     *
     * @throws Exception
     */
    private function passwordCheck(&$user)
    {
        if (empty($user['password']) || empty($user['cpassword']))
            throw new \Exception('The two passwords cannot be empty!');
        if ($user['password'] != $user['cpassword'])
            throw new \Exception('The two passwords must be identical!');
        if (strlen($user['password']) < 6)
            throw new \Exception('The passwords must be at least 6 characters long!');

        if (isset($user['oldpassword'])) {
            if ($user['password'] == $user['oldpassword'])
                throw new \Exception('The passwords are the same! (Old and new one)');

            if (!Hash::check($user['oldpassword'], Auth::user()->password))
                throw new \Exception('The password you entered is not valid!');
        }
    }

    /**
     * Does the checks for user information.
     *
     * @param array $user User information.
     *
     * @throws Exception
     */
    private function userCheck(&$user)
    {
        if (empty($user['username']))
            throw new \Exception('Username cannot be empty!');
        if (strlen($user['username']) < 4)
            throw new \Exception('The username should be at least 4 characters long!');

        if (empty($user['email']))
            throw new \Exception('Email cannot be empty!');

        if (empty($user['firstname']))
            throw new \Exception('First name cannot be empty!');
        if (empty($user['lastname']))
            throw new \Exception('Last name cannot be empty!');
        if (strlen($user['firstname']) < 2 || strlen($user['lastname']) < 2)
            throw new \Exception('The first and last name should be at least 2 characters long!');
    }

    public function deleteToken($token)
    {
        $token = Token::where('uuid', $token)->where('user_id', Auth::user()->id)->firstOrFail();

        $token->delete();

        return Redirect::to('account');
    }

}
