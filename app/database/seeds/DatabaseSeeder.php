<?php
/**
 * Seeds the database with data.
 *
 * @author  Valentin Duricu <valentin@duricu.ro>
 * @date    19.04.2015
 * @see     User, UserController
 * @license CC BY-SA 4.0
 */


/**
 * Database seeder class.
 */
class DatabaseSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Eloquent::unguard();

        $this->call('UsersSeeder');
    }

}
