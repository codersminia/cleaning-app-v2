<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AreaTask extends Model
{
    use HasFactory;

    protected $fillable = [
        'proposal_area_id',
        'task_id',
        'custom_description',
        'custom_frequency_id',
    ];

    public function proposalArea()
    {
        return $this->belongsTo(ProposalArea::class);
    }

    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function customFrequency()
    {
        return $this->belongsTo(Frequency::class, 'custom_frequency_id');
    }
}