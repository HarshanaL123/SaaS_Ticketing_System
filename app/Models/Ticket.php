<?php

namespace App\Models;

use App\Enums\TicketPriority;
use App\Enums\TicketStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder; 

#[Fillable(['user_id', 'agent_id', 'title', 'description', 'status', 'priority'])]
class Ticket extends Model
{
    use HasFactory; 

    protected function casts(): array
    {
        return [
            'status' => TicketStatus::class,
            'priority' => TicketPriority::class,
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function agent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    public function activities(): HasMany
    {
        return $this->hasMany(TicketActivity::class);
    }

    public function scopeOpen(Builder $query): Builder
    {
        return $query->where('status', TicketStatus::OPEN->value);
    }

    public function scopeHighPriority(Builder $query): Builder
    {
        return $query->where('priority', TicketPriority::HIGH->value);
    }

    public function scopeForCustomer(Builder $query, User $user): Builder
    {
        return $query->where('user_id', $user->id);
    }
}
