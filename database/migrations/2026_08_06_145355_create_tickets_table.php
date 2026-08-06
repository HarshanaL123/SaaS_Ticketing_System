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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(\App\Models\User::class, 'user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignIdFor(\App\Models\User::class, 'agent_id')->nullable()->constrained('users')->nullOnDelete();

            $table->string('title');
            $table->text('description');

            $table->string('status')->default(\App\Enums\TicketStatus::OPEN->value);
            $table->string('priority')->default(\App\Enums\TicketPriority::MEDIUM->value);

            $table->timestamps();
        });
    } 

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
