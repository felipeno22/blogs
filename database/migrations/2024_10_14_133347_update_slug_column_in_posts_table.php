<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateSlugColumnInPostsTable extends Migration
{
    public function up()
    {
        // Adiciona a restrição de unicidade na coluna 'slug'
        Schema::table('posts', function (Blueprint $table) {
            $table->string('slug')->unique()->change();
        });
    }

    public function down()
    {
        // Remove a restrição de unicidade no rollback
        Schema::table('posts', function (Blueprint $table) {
            $table->string('slug')->change(); // Removendo apenas a unicidade
        });
    }
}
