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
        $tableNames = config('permission.table_names');
        $columnNames = config('permission.column_names');

        Schema::create($tableNames['model_has_modules'], function (Blueprint $table) use ($tableNames, $columnNames) {
            $table->unsignedInteger('module_id');

            $table->string('model_type');
            $table->unsignedBigInteger($columnNames['model_morph_key']);
            $table->index([$columnNames['model_morph_key'], 'model_type',]);

            $table->foreign('module_id')
                ->references('id')
                ->on($tableNames['modules'])
                ->onDelete('cascade');

            $table->primary(['module_id', $columnNames['model_morph_key'], 'model_type'],
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
