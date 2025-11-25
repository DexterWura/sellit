<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddListingTypeToDomainsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('domain_posts', function (Blueprint $table) {
            $table->enum('listing_type', ['domain', 'website', 'social_media'])->default('domain')->after('id');
            $table->string('website_url')->nullable()->after('name');
            $table->string('social_platform')->nullable()->after('website_url');
            $table->string('social_username')->nullable()->after('social_platform');
            $table->text('analytics_data')->nullable()->after('description');
            $table->text('additional_links')->nullable()->after('analytics_data');
            $table->boolean('is_verified')->default(false)->after('status');
            $table->enum('verification_method', ['dns', 'file_upload', 'credentials'])->nullable()->after('is_verified');
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
            $table->dropColumn([
                'listing_type',
                'website_url',
                'social_platform',
                'social_username',
                'analytics_data',
                'additional_links',
                'is_verified',
                'verification_method'
            ]);
        });
    }
}


