<?php
/**
 * Updates the versions table to allow filename and checksum addition.
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
class AttachFileVersionsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('Versions', function (Blueprint $table) {
            $table->string("filename", 255);
            $table->string("checksum", 255);

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
        Schema::table('Versions', function (Blueprint $table) {
            $table->dropColumn(["filename", "checksum", "created_at", "updated_at"]);
        });
    }

}
