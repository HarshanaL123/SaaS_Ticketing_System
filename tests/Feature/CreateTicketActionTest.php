<?php

use App\Actions\Tickets\CreateTicketAction;
use App\Models\User;
use App\Models\Ticket;
use Illuminate\Foundation\Testing\RefreshDatabase;

test('it creates a ticket and generates an audit trail activity', function () {
    //1. Arrange Create a customer 
    $customer = User::factory()->create(['role' => 'customer']);

    $action = app(CreateTicketAction::class);

    $ticketData = [
        'title' => 'My Server is Down',
        'description' => 'I cannot access the main dashboard',
        'priority' => 'high',
    ];

    //2. Act: Execute the action directly (bypass the controller)
    $ticket = $action->execute($customer, $ticketData);

    //3. Assert: Check the tickets table
    $this->assertDatabaseHas('tickets', [
        'id' => $ticket->id,
        'title' => 'My Server is Down',
        'user_id' => $customer->id,
        'status' => 'open',
    ]);

    //4. Assert: Check the Event Sourced Audit Trail
    $this->assertDatabaseHas('ticket_activities', [
        'ticket_id' => $ticket->id,
        'actor_id' => $customer->id,
        'type' => 'created',
    ]);
});