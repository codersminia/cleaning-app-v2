<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProposalAreaType extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    // If you need to access proposal_areas related to this area type
    public function proposalAreas()
    {
        return $this->hasMany(ProposalArea::class, 'area_type_id');
    }
}