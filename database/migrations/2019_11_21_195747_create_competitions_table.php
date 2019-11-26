<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompetitionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('competitions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('area_id')
                ->unsigned()
                ->foreign('area_id')
                ->references('id')
                ->on('area')
                ->onDelete('cascade');
            $table->string('name', 100);
            $table->string('code', 5)->nullable();
            $table->longText('emblemUrl')->nullable();
            $table->string('plan', 50)->nullable();
            $table->bigInteger('curseason_id')
                ->nullable()
                ->foreign('curseason_id')
                ->references('id')
                ->on('current_season')
                ->onDelete('cascade');
            $table->integer('numberOfAvailableSeasons');
            $table->string('lastUpdated', 25);
            $table->integer('cpt_estado')->default(1);
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
        Schema::dropIfExists('competitions');
    }
}
