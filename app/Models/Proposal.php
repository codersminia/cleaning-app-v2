<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Proposal extends Model
{
    use HasFactory;

    protected $fillable = [
        'prospect_id', 'proposal_name', 'note',
        'proposal_type', 'commercial_category', 'residential_category',
        'facility_id','phase','status', 'created_by'
    ];

    public function weekdays()
    {
        return $this->belongsToMany(Weekday::class, 'proposal_weekdays', 'proposal_id', 'weekday_id');
    }

    // Add this new relationship
    public function proposalAreas()
    {
        return $this->hasMany(ProposalArea::class);
    }

    public function prospect()
    {
        return $this->belongsTo(Prospect::class); // Assuming you have a Prospect model
    }

    public function calculations()
    {
        // hasOne looks for 'proposal_id' in the 'proposal_calculations' table
        return $this->hasOne(ProposalCalculation::class, 'proposal_id');
    }

    public function recipients()
    {
        return $this->hasMany(ProposalRecipient::class);
    }

    public function signatures()
    {
        return $this->hasMany(ProposalSignature::class);
    }
}