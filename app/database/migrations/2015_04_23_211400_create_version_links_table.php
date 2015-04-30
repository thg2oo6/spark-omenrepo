<?php
/**
 * Creates the project links table into the database.
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
class CreateVersionLinksTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('VersionLinks', function (Blueprint $table) {
            $table->bigInteger("parent_id")->unsigned(); /* Parent Version */
            $table->bigInteger("child_id")->unsigned(); /* Child Version */

            $table->primary(["parent_id", "child_id"]);
            $table->foreign('parent_id')->references('id')->on("Versions");
            $table->foreign('child_id')->references('id')->on("Versions");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('VersionLinks');
    }

}
