<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('progress_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('goal_id')->constrained()->cascadeOnDelete();
            $table->text('note');
            $table->unsignedTinyInteger('progress_value');
            $table->timestamp('logged_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('progress_logs');
    }
};
