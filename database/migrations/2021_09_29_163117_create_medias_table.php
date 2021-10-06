<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMediasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('medias', function (Blueprint $table) {
            $table->id();
            $table->text('caption');
            $table->integer('comments_count');
            $table->string('media_product_type', 50);
            $table->string('media_id', 255);
            $table->integer('like_count');
            $table->string('media_type', 50);
            $table->string('media_url', 500);
            $table->string('permalink', 255);
            $table->timestamp('timestamp');
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
        Schema::dropIfExists('medias');
    }
}
