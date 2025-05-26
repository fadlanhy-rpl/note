<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reminders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('note_id')->constrained()->onDelete('cascade');
            $table->timestamp('remind_at'); // Waktu reminder akan aktif
            $table->text('description')->nullable(); // Deskripsi singkat untuk reminder (opsional)
            $table->boolean('is_recurring')->default(false); // Apakah reminder berulang? (fitur lanjutan)
            $table->string('recurring_frequency')->nullable(); // Misal: daily, weekly, monthly (jika is_recurring true)
            $table->boolean('is_completed')->default(false); // Status reminder
            $table->timestamp('completed_at')->nullable(); // Kapan diselesaikan
            $table->timestamps(); // created_at, updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reminders');
    }
};