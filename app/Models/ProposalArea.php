<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProposalArea extends Model
{
    use HasFactory;

    protected $fillable = [
        'proposal_id',
        'area_type_id',
        'notes',
        'rooms',
        'hardfloor',
        'carpet',
        'hours',
        'minutes',
        'sqft',
    ];

    protected $casts = [
        'hardfloor' => 'boolean',
        'carpet' => 'boolean',
    ];

    public function proposal()
    {
        return $this->belongsTo(Proposal::class);
    }

    public function areaType()
    {
        return $this->belongsTo(ProposalAreaType::class, 'area_type_id');
    }

    public function areaTasks()
    {
        return $this->hasMany(AreaTask::class);
    }
}