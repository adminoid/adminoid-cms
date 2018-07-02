<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Kalnoy\Nestedset\NestedSet;

class CreatePagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->string('title')->nullable();
            $table->text('content')->nullable();
            $table->string('template')->default('pages.simple');
            $table->string('slug')->nullable();
            $table->string('slug_backup')->nullable();
            $table->string('uri')->unique()->nullable();
            $table->string('uri_backup')->nullable();
            $table->boolean('show_in_main_menu')->default(true);
            $table->boolean('active')->default(false);
            $table->boolean('locked_content')->default(false);
            $table->boolean('locked_move')->default(false);
            $table->boolean('hide_submenu')->default(false);
            $table->integer('user_id')->nullable();
            NestedSet::columns($table);
            $table->timestamps();
            $table->unique(['parent_id', 'slug']);
            $table->softDeletes();
            $table->string('vue_component')->nullable(); // For appointing custom vue components for admin panel
            // Blank for extend Page in future
            $table->integer('pageable_id')->nullable();
            $table->string('pageable_type')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
//        Schema::dropIfExists('pages');
        Schema::table('pages', function (Blueprint $table) {
            NestedSet::dropColumns($table);
        });
    }
}
