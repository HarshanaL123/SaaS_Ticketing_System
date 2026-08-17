<?php

use App\Models\User;
use App\Models\Ticket;
use Illuminate\Foundation\Testing\RefreshDatabase;

test('a customer cannot view another customers ticket', function () {
    // Arrange: Create two different customers 
    $hackerCustomer = User::factory()->create(['role' => 'customer']);
    $innocentCustomer = User::factory()->create(['role' => 'customer']);
    
    // Create a ticket belonging to the innocent customer 
    $ticket = Ticket::factory()->create([
        'user_id' => $innocentCustomer->id,
    ]);

    // Act: The hacker tries to view the innocent customer's ticket 
    $response = $this->actingAs($hackerCustomer)->get(route('tickets.show', $ticket));

    // Assert: The system must reject them with a forbidden status
    $response->assertStatus(403);
});

test('a support agent can update any ticket status', function () {
    // Arrange: Create a customer, a ticket, and an agent
    $customer = User::factory()->create(['role' => 'customer']);
    $agent = User::factory()->create(['role' => 'agent']);

    $ticket = Ticket::factory()->create([
        'user_id' => $customer->id,
        'status' => 'open',
    ]);

    // Act: The agent tries to update the status to in_progress
    $response = $this->actingAs($agent)->patch(route('tickets.status.update', $ticket), [
        'status' => 'in_progress',
    ]);

    // Assert: It should redirect back with success, and the database should be updated
    $response->assertStatus(302);

    $this->assertDatabaseHas('tickets', [
        'id' => $ticket->id,
        'status' => 'in_progress'
    ]);
});