<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddImagesToDomainsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('domain_posts', function (Blueprint $table) {
            $table->string('thumbnail')->nullable()->after('description');
            $table->text('images')->nullable()->after('thumbnail'); // JSON array of image paths
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('domain_posts', function (Blueprint $table) {
            $table->dropColumn(['thumbnail', 'images']);
        });
    }
}

