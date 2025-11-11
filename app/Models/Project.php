<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Project extends Model
{
    use HasFactory;
    protected $fillable = [
        'proposal_id',
        'area_ids',
        'service_type_id',
        'is_recurring',
        'frequency_id',
        'per',
        'notes',
    ];

    public function projectTasks()
    {
        return $this->hasMany(ProjectTask::class);
    }
    
}
