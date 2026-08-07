<?php

namespace App\Actions\Tickets;

use App\Enums\TicketStatus;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UpdateTicketStatusAction
{
    public function execute(Ticket $ticket, User $actor, TicketStatus $newStatus): Ticket
    {
        // didn't change the status, just return early
        if($ticket->status === $newStatus) {
            return $ticket;
        }

        return DB::transaction(function () use ($ticket, $actor, $newStatus) {
            $oldStatus = $ticket->status;

            $ticket->update([
                'status' => $newStatus->value,
            ]);

            // write the audit trail
            $ticket->activities()->create([
                'actor_id' => $actor->id,
                'type' => 'status_changed',
                'old_value' => $oldStatus->value,
                'new_value' => $newStatus->value,
                'comment' => "Status changes from {$oldStatus->label()} to {$newStatus->label()}.",
            ]);

            // dispatch the redis background queue job

            return $ticket;
        });
    }
} 