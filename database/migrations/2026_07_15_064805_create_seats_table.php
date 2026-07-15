<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hall_id')->constrained()->cascadeOnDelete();
            $table->foreignId('seat_type_id')->constrained()->cascadeOnDelete();
            $table->string('row', 10);
            $table->integer('number');
            $table->string('label', 10);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['hall_id', 'label']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seats');
    }
};
