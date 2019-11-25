<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWinnerSeasonTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('winner_season', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('curseason_id')
                ->unsigned()
                ->foreign('curseason_id')
                ->references('id')
                ->on('current_season')
                ->onDelete('cascade');
            $table->string('name', 100);
            $table->string('shortName', 50)->nullable();
            $table->char('tla', 3)->nullable();
            $table->longText('crestUrl')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('winner_season');
    }
}
