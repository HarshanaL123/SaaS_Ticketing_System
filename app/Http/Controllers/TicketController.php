<?php

namespace App\Http\Controllers;

use App\Actions\Tickets\CreateTicketAction;
use App\Actions\Tickets\UpdateTicketStatusAction;
use App\Actions\Tickets\AddTicketCommentAction;
use App\Enums\TicketStatus;
use App\Http\Requests\StoreTicketRequest;
use App\Http\Requests\UpdateTicketStatusRequest;
use App\Http\Resources\TicketResource;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class TicketController extends Controller
{
    // Display a listing of the tickets
    public function index(Request $request): Response
    {
        // 1. Utilize the 'customer' and 'agent' relationships
        // 2. Utilize the custom Eloquent Scope: forCustomer()
        $tickets = Ticket::with(['customer', 'agent'])
            ->when($request->user()->isCustomer(), fn($q) => $q->forCustomer($request->user()))
            // Dynamically filter the database based on the URL query string. 
            ->when($request->filled('status'), fn($q) => $q->where('status', $request->status))
            ->latest()
            ->paginate(15)
            ->withQueryString(); // using this will prevents pagination links from dropping the filters. 

        return Inertia::render('Tickets/Index', [
            // wrap the paginated results in the strict JSON contract.
            'tickets' => TicketResource::collection($tickets),
            // pass the current filters back to vue. 
            'filters' => $request->only(['status']),
        ]);
    }

    // Store a newly created ticket.
    public function store(StoreTicketRequest $request, CreateTicketAction $action): RedirectResponse
    {
        // The form request already validated the data. Only need to pass the data to action class. 
        $ticket = $action->execute($request->user(), $request->validated());

        return redirect()->route('tickets.show', $ticket)->with('success', 'Ticket created successfully.');
    }

    // Display the specified ticket.
    public function show(Ticket $ticket, Request $request): Response
    {
        // Security check: prevent customers form viewing other people's tickets
        if ($request->user()->isCustomer() && $ticket->user_id !== $request->user()->id) {
            abort(403, 'Unauthorized access to this ticket.');
        }

        // Load the ticket with all relationships.
        $ticket->load(['customer', 'agent', 'activities.actor']);

        return Inertia::render('Tickets/Show', [
            'ticket' => new TicketResource($ticket),
        ]);
    }

    // Update the status of the specified ticket.
    public function updateStatus(UpdateTicketStatusRequest $request, Ticket $ticket, UpdateTicketStatusAction $action): RedirectResponse 
    {
        // The Form Request already verified they are agent, and validated the Enum status.
        $action->execute(
            $ticket,
            $request->user(),
            TicketStatus::from($request->status)
        );

        return back()->with('success', 'Ticket status updated.');
    }

    // Add a comment to the spedified ticket.
    public function addComment(Request $request, Ticket $ticket, AddTicketCommentAction $action): RedirectResponse
    {
        $request->validate([
            'comment' => ['required', 'string', 'max:1000'],
        ]);

        $action->execute($ticket, $request->user(), $request->comment);

        return back()->with('success', 'Comment added.');
    }
}
