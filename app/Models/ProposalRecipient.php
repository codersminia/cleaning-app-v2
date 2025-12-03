<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProposalRecipient extends Model
{
    use HasFactory;

    protected $table = 'proposal_recipients';

    protected $fillable = [
        'proposal_id',
        'email',
        'unique_token',
        'opened_at'
    ];

    // Relationship: A recipient belongs to a Proposal
    public function proposal()
    {
        return $this->belongsTo(Proposal::class);
    }
    
    // Relationship: A recipient has many activity logs
    public function logs()
    {
        return $this->hasMany(ProposalTrackingLog::class, 'recipient_id');
    }
}