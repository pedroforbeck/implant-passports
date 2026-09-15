<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('devices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->foreignId('manufacturer_id')->constrained()->restrictOnDelete();
            $table->string('type', 30);
            $table->string('model');
            $table->string('serial_number', 100)->unique();
            $table->date('implanted_at');
            $table->string('hospital')->nullable();
            $table->boolean('mri_conditional')->default(false);
            $table->string('status', 20)->default('ativo');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('devices');
    }
};
