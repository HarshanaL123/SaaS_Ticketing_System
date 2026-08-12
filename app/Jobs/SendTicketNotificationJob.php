<?php

namespace App\Jobs;

use App\Contracts\NotificationInterface;
use App\Models\Ticket;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendTicketNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public Ticket $ticket,
        public string $message,
        public NotificationInterface $strategy
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // The job just call the send() without focusing on email or slack and this is the polymorphism. 
        $this->strategy->send($this->ticket, $this->message);
    }
}
