<?php
/**
 * Creates the many to many bridge between keywords and projects tables into the database.
 *
 * @author  Valentin Duricu <valentin@duricu.ro>
 * @date    19.04.2015
 * @license CC BY-SA 4.0
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Creates the many to many bridge between keywords and projects tables into the database.
 */
class CreateProjectKeywordTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Project_Keyword', function (Blueprint $table) {
            $table->bigInteger("project_id")->unsigned();
            $table->bigInteger("keyword_id")->unsigned();

            $table->primary(["project_id", "keyword_id"]);
            $table->foreign('project_id')->references('id')->on("Projects");
            $table->foreign('keyword_id')->references('id')->on("Keywords");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('Project_Keyword');
    }

}
