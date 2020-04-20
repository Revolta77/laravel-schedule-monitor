<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCronsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('crons', function (Blueprint $table) {
            $table->increments('id');
            $table->string('command');
            $table->bigInteger('success')->default('0');
			$table->bigInteger('error')->default('0');
            $table->text('description')->nullable();
            $table->text('parameters')->nullable();
            $table->text('expression')->nullable();
			$table->text('timezone')->default('UTC');
			$table->tinyInteger('is_active');
			$table->tinyInteger('dont_overlap');
			$table->tinyInteger('run_in_maintenance');
			$table->text('notification_email_address')->nullable();
			$table->text('notification_phone_number')->nullable();
			$table->text('notification_slack_webhook')->nullable();

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
        Schema::dropIfExists('crons');
    }
}
