<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostmanTables extends Migration
{
    public function up()
    {
        Schema::create('postman_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('collection_id');
            $table->string('request_name');
            $table->string('method');
            $table->string('url');
            $table->json('headers')->nullable();
            $table->json('auth')->nullable();
            $table->json('body')->nullable();
            $table->json('events')->nullable();
            $table->timestamps();

            $table->foreign('collection_id')->references('id')->on('postman_collections')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('postman_requests');
        Schema::dropIfExists('postman_collections');
    }
}
