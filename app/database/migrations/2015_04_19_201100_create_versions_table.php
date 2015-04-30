<?php
/**
 * Creates the project versions table into the database.
 *
 * @author  Valentin Duricu <valentin@duricu.ro>
 * @date    19.04.2015
 * @license CC BY-SA 4.0
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Creates the project versions table into the database.
 */
class CreateVersionsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Versions', function (Blueprint $table) {
            $table->bigIncrements("id")->unsigned();
            $table->bigInteger("project_id")->unsigned();
            $table->string("version", 60);
            $table->longText("omenFile");

            $table->foreign('project_id')->references('id')->on("Projects");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('Versions');
    }

}
