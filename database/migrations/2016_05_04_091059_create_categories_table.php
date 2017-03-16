<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->default('')->comment('分类名称');
            $table->integer('pid')->default(0)->comment('分类关系');
            $table->string('url')->default('')->comment('菜单链接地址');
            $table->string('icon')->default('')->comment('图标');
            $table->tinyInteger('sort')->default(0)->comment('排序');
            $table->tinyInteger('status')->default(1)->comment('状态');
            $table->tinyInteger('type')->default(1)->comment('类型');
            $table->text('content_html')->comment('文章内容-html格式');
            $table->text('content_mark')->comment('文章内容-markdown格式');
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
        Schema::drop('categories');
    }
}
