<?php
/**
 * Creates the users table into the database.
 *
 * @author  Valentin Duricu <valentin@duricu.ro>
 * @date    19.04.2015
 * @license CC BY-SA 4.0
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Creates the users table into the database.
 */
class CreateUsersTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Users', function (Blueprint $table) {
            $table->bigIncrements("id")->unsigned();
            $table->string("username", 60)->unique();
            $table->text("email");
            $table->string("password", 255);
            $table->string("firstname", 120);
            $table->string("lastname", 120);
            $table->string("activationCode", 120);
            $table->boolean('isAdmin')->defaule(false);
            $table->boolean('isActive')->default(false);
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('Users');
    }

}
