<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMemberUserPivotTable extends Migration
{
    public function up()
    {
        Schema::create('member_user', function (Blueprint $table) {
            $table->integer('member_id')->unsigned()->index();
            $table->foreign('member_id')->references('id')->on('members')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->integer('user_id')->unsigned()->index();
            $table->foreign('user_id')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->primary(['user_id', 'member_id']);

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('member_user');
    }
}
