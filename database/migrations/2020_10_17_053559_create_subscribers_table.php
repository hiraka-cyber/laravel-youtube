<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscribersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscribers', function (Blueprint $table) {
            $table->unsignedInteger('subscribing_id')->comment('フォローしているユーザID');
            $table->unsignedInteger('subscribed_id')->comment('フォローされているユーザID');

            $table->index('subscribing_id');
            $table->index('subscribed_id');

            $table->unique([
                'subscribing_id',
                'subscribed_id'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subscribers');
    }
}
