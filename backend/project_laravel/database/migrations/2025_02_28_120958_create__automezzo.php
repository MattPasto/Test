<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('automezzo', function (Blueprint $table) {
            $table->bigInteger('id',true);
            $table->bigInteger('filiale_id');
            $table->string('codice');
            $table->string('targa');
            $table->string('marca');
            $table->string('modello');
            $table->foreign(["filiale_id"])->references('id')->on('filiale')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('automezzo');
    }
};
