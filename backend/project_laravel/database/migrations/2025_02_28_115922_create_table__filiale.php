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
        Schema::create('filiale', function (Blueprint $table) {
            $table->bigInteger('id',true);
            $table->string('codice');
            $table->string('indirizzo');
            $table->string('città');
            $table->string('cap');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('filiale');
    }
};
