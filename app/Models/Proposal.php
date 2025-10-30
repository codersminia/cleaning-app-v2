<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Proposal extends Model
{
    protected $fillable = [
        'prospect_id', 'proposal_name', 'note',
        'proposal_type', 'commercial_category', 'residential_category',
        'facility_id','phase','status', 'created_by'
    ];

    public function weekdays()
    {
        return $this->belongsToMany(Weekday::class, 'proposal_weekdays', 'proposal_id', 'weekday_id');
    }
}
