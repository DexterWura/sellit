<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBidConversationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bid_conversations', function (Blueprint $table) {
            $table->id();
            $table->integer('bid_id')->default(0);
            $table->integer('buyer_id')->default(0);
            $table->integer('seller_id')->default(0);
            $table->integer('admin_id')->default(0);
            $table->text('messages')->nullable();
            $table->tinyInteger('status')->default(0);
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
        Schema::dropIfExists('bid_conversations');
    }
}
