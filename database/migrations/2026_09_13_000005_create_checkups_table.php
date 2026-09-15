<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('checkups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('device_id')->constrained()->cascadeOnDelete();
            $table->foreignId('doctor_id')->nullable()->constrained('users')->nullOnDelete();
            $table->date('checked_at');
            $table->unsignedTinyInteger('battery_level');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['device_id', 'checked_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('checkups');
    }
};
