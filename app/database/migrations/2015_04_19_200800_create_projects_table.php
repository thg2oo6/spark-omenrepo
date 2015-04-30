<?php
/**
 * Creates the projects table into the database.
 *
 * @author  Valentin Duricu <valentin@duricu.ro>
 * @date    19.04.2015
 * @license CC BY-SA 4.0
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Creates the projects table into the database.
 */
class CreateProjectsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Projects', function (Blueprint $table) {
            $table->bigIncrements("id")->unsigned();
            $table->bigInteger("user_id")->unsigned();
            $table->string("name", 255);
            $table->longText("description")->nullable();
            $table->text("homepage")->nullable();
            $table->text("license")->nullable();
            $table->timestamps();

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
        Schema::drop('Projects');
    }

}
