<?php

namespace App\Actions\Tickets;

use App\Jobs\SendTicketNotificationJob;
use App\Services\Notifications\EmailNotificationStrategy;
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

            SendTicketNotificationJob::dispatch(
                $ticket,
                "New comment added: {$comment}",
                new EmailNotificationStrategy()
            );

            return $ticket;
        });
    }
}