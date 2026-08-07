<?php

namespace App\Actions\Tickets;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CreateTicketAction 
{
    public function execute(User $customer, array $data): Ticket
    {
        return DB::transaction(function () use ($customer, $data){
            // create the ticket
            $ticket = Ticket::create([
                'user_id' => $customer->id,
                'title' => $data['title'],
                'description' => $data['description'],
                'priority' => $data['priority'] ?? \App\Enums\TicketPriority::MEDIUM->value,
            ]);

            // write the initial audit trail
            $ticket->activities()->create([
                'actor_id' => $customer->id,
                'type' => 'created',
                'comment' => 'Ticket opened by customer.',
            ]);

            return $ticket;
        });
    }
}