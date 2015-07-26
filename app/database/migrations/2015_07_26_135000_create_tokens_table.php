<?php
/**
 * Creates the users token table into the database.
 *
 * @author  Valentin Duricu <valentin@duricu.ro>
 * @date    26.07.2015
 * @license CC BY-SA 4.0
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Creates the users table into the database.
 */
class CreateTokensTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Tokens', function (Blueprint $table) {
            $table->string("uuid");
            $table->bigInteger("user_id")->unsigned();
            $table->text("computerinfo");
            $table->timestamps();

            $table->primary('uuid', 'user_id');
            $table->foreign('user_id')->references('id')->on("Users");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('Tokens');
    }

}
