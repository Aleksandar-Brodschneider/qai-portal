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
        Schema::create('research', function (Blueprint $table) {
            $table->id();

            // kdo je vnesel raziskavo (login user)
            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            // vsebina raziskave
            $table->string('title');
            $table->string('authors');          // npr. "Wichert, A.; Dunjko, V."
            $table->text('abstract')->nullable();
            $table->smallInteger('year')->nullable();
            $table->string('doi')->nullable();

            $table->timestamps();

            // indeksi (za iskanje)
            $table->index('title');
            $table->index('authors');
            $table->index('year');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('research');
    }
};
