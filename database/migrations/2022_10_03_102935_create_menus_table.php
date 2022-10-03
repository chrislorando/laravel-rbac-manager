<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->tinyInteger('sort')->default(1);
            $table->string('name');
            $table->string('url');
            $table->string('route');
            $table->enum('position', ['topbar', 'sidebar'])->default('1');
            $table->enum('status', ['active', 'inactive'])->default('1');
            $table->string('description')->nullable();
            $table->timestamps();

            $table->foreign('parent_id')
            ->references('id')
            ->on('menus')
            ->onUpdate('CASCADE')
            ->onDelete('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('menus');
    }
};
