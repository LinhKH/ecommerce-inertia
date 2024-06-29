<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateBannerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('banner', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->string('pagelink');
            $table->boolean('status')->default(1);
            $table->string('banner_img');
            $table->timestamps();
        });

        DB::table('banner')->insert([
            ['title' => 'Banner First',
            'pagelink' => 'https://www.yahoobaba.net',
            'status' => '1',
            'banner_img' => 'b1.jpg'],
            ['title' => 'Banner Second',
            'pagelink' => 'https://www.yahoobaba.net',
            'status' => '1',
            'banner_img' => 'b2.jpg'],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('banner');
    }
}
