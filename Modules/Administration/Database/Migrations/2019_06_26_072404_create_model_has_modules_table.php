<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateModelHasModulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('model_has_modules', function (Blueprint $table) {
            $table->unsignedInteger('module_id');

            $table->string('model_type');
            $table->unsignedBigInteger('model_id');
            $table->index(['model_id', 'model_type',]);

            $table->foreign('module_id')->references('id')->on('modules')->onDelete('cascade');

            $table->primary(['module_id', 'model_id', 'model_type'],
                    'model_has_modules_module_model_type_primary');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('model_has_modules');
    }
}
