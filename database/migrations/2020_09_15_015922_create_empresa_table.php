<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmpresaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('empresa', function (Blueprint $table) {
            $table->increments('idEmpresa');
            $table->string('razonSocial', 50)->unique();
            $table->string('ruc', 11)->unique();
            $table->string('urlFacebook', 50)->nullable();
            $table->string('urlInstagram', 50)->nullable();
            $table->string('urlWhatsApp', 50)->nullable();
            $table->string('correo', 30)->nullable();
            $table->string('logo', 50)->nullable();
            $table->boolean('vigencia')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('empresa');
    }
}
