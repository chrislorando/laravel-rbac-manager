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
        Schema::create('permissions', function (Blueprint $table) {
            $table->bigIncrements('id'); 
            $table->string('name');
            $table->string('url');
            $table->string('route');
            $table->string('params');
            $table->string('method');
            $table->string('ctrl_path');
            $table->string('ctrl_name');
            $table->string('ctrl_action');
            $table->enum('type', ['auth', 'guest']);
            $table->string('guard_name'); 
            $table->string('description'); 
            $table->timestamps();
            $table->unique(['name', 'guard_name']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('permissions');
    }
};
