<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProposalCalculation extends Model
{
    use HasFactory;

    // 1. Link to your specific table
    protected $table = 'proposal_calculations';

    // 2. Allow mass assignment
    protected $guarded = [];

    // 3. Automatically convert the JSON column to an Array when you access it
    protected $casts = [
        'calculator_data' => 'array',
    ];

    public function proposal()
    {
        return $this->belongsTo(Proposal::class);
    }
}