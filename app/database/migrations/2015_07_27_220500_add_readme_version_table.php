<?php
/**
 * Adds the readme to the version table.
 *
 * @author  Valentin Duricu <valentin@duricu.ro>
 * @date    27.07.2015
 * @license CC BY-SA 4.0
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Adds the readme to the version table.
 */
class AddReadmeVersionTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('Versions', function (Blueprint $table) {
            $table->longText("readme");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('Versions', function (Blueprint $table) {
            $table->dropColumn(["readme"]);
        });
    }

}
