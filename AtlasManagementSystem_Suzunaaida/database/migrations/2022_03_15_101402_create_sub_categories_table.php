<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sub_categories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('main_category_id')->index()->comment('メインカテゴリーID');
            $table->string('sub_category', 60)->comment('サブカテゴリー名');
            $table->timestamps();

            $table->foreign('main_category_id')->references('id')->on('main_categories')->onDelete('cascade');
            $table->unique(['main_category_id', 'sub_category']);
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sub_categories');
    }
}
