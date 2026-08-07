<?php

namespace App\Actions\Tickets;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AssignTicketAction
{
    public function execute(Ticket $ticket, User $agent): Ticket
    {
        return DB::transaction(function() use ($ticket, $agent){
            $oldAgentId = $ticket->agent_id;

            $ticket->update([
                'agent_id' => $agent->id,
            ]);

            $ticket->activities()->create([
                'actor_id' => $agent->id,
                'type' => 'agent_assigned',
                'old_value' => (string) $oldAgentId,
                'new_value' => (string) $agent->id,
                'comment' => "Ticket assigned to agent {$agent->name}.",
            ]);

            return $ticket;
        });
    }
}