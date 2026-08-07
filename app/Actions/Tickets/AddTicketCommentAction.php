<?php

namespace App\Actions\Tickets;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AddTicketCommentAction {
    public function execute(Ticket $ticket, User $actor, string $comment): Ticket
    {
        return DB::transaction(function() use ($ticket, $actor, $comment){

            $ticket->activities()->create([
                'actor_id' => $actor->id,
                'type' => 'comment_added',
                'comment' => $comment,
            ]);

            return $ticket;
        });
    }
}