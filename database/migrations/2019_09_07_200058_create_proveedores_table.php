<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProveedoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('proveedores', function (Blueprint $table) {
            // 
                $table->integer('id')->unsigned();
                $table->string('contacto', 50)->nullable();
                $table->string('telefono_contacto', 50)->nullable();

                /* Llave foránea id de la tabla proveedores referenciando al id de la tabla personas y con eliminación en cascada */
                    $table->foreign('id')->references('id')->on('personas')->onDelete('cascade'); 
            // 
            // $table->timestamps(); /* No se crean las columnas created_at y updated_at */
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('proveedores');
    }
}
