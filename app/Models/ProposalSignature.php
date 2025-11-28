<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProposalSignature extends Model
{
    use HasFactory;

    protected $table = 'proposal_signatures';
    
    // Allow mass assignment for these fields
    protected $fillable = [
        'proposal_id',
        'signer_name',
        'signer_title',
        'signer_email',
        'signature_image',
        'ip_address',
        'signed_at'
    ];

    protected $casts = [
        'signed_at' => 'datetime',
    ];

    public function proposal()
    {
        return $this->belongsTo(Proposal::class);
    }
}