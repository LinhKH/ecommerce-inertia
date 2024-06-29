<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateGeneralSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('general_settings', function (Blueprint $table) {
            $table->id();
            $table->string('site_logo')->nullable();
            $table->string('site_name');
            $table->string('site_title');
            $table->string('theme_color');
            $table->string('copyright');
            $table->string('currency');
            $table->string('description');
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('address')->nullable();
            $table->timestamps();
        });

        DB::table('general_settings')->insert([
            'site_name' => 'Yahoo Cart',
            'site_logo' => 'ecomm-logo.png',
            'site_title' => 'Yahoo Cart',
            'theme_color' => '#B34590',
            'copyright' => 'Copyright © 2022-2023',
            'currency' => '$',
            'description' => 'Suspendisse et hendrerit felis. In augue tellus, aliquet consectetur auctor a, dictum in neque. Mauris finibus rutrum porta. Mauris in eleifend nisi, vel sodales nisl.',
            'phone' => '1523652012',
            'email' => 'site@email.com',
            'address' => 'New York,USA',
        ]);

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('general_settings');
    }
}
