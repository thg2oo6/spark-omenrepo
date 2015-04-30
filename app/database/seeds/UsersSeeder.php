<?php
/**
 * Creates the first user of the Omen Repository application.
 *
 * @author  Valentin Duricu <valentin@duricu.ro>
 * @date    19.04.2015
 * @license CC BY-SA 4.0
 */

/**
 * Seeder class for user model
 */
class UsersSeeder extends Seeder
{
    /**
     * Creates the elements in the database.
     */
    public function run()
    {
        User::create([
            "username"  => "admin",
            "password"  => "parola123",
            "email"     => "admin@localhost",
            "firstname" => "Admin",
            "lastname"  => "User",
            "isActive"  => 1,
            "isAdmin"   => 1
        ]);
    }


} 