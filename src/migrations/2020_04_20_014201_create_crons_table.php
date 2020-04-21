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
			$table->text('timezone');
			$table->dateTime('run_at')->nullable();
			$table->dateTime('next_run_at')->nullable();
			$table->tinyInteger('is_active');
			$table->tinyInteger('overlaps');
			$table->tinyInteger('maintenance');

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
