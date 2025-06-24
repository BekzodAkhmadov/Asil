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
        Schema::table('wasyt_new.product_brand', function (Blueprint $table) {
            $table->foreign('creator_id')->references('id')->on('base.user')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('company_id')->references('id')->on('base.company')->onUpdate('cascade')->onDelete('restrict');
        });
    }
};
