<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Base\App\Helpers\MigrationHelper;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wasyt_new.product_brand', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('creator_id')->index();
            $table->bigInteger('company_id')->nullable()->index();
            $table->string('name');
            $table->string('image')->nullable();
            $table->timestamp('created_at')->index();
            $table->timestamp('updated_at');
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
        Schema::dropIfExists('wasyt_new.product_brand');
    }
};
