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
        Schema::create('ticket_activities', function (Blueprint $table) {
            $table->id();

            // links back to the ticket and the user who performed the action
            $table->foreignIdFor(\App\Models\Ticket::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(\App\Models\User::class, 'actor_id')->nullable()->constrained('users')->nullOnDelete();

            // the audit data
            $table->string('type');
            $table->string('old_value')->nullable();
            $table->string('new_value')->nullable();
            $table->text('comment')->nullable();
            $table->timestamps();
        });
    }

    /** 
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_activities');
    }
};
