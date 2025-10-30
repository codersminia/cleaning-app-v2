<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'default_frequency_id',
    ];

    public function defaultFrequency()
    {
        return $this->belongsTo(Frequency::class, 'default_frequency_id');
    }

    // If you need to access area_tasks related to this task
    public function areaTasks()
    {
        return $this->hasMany(AreaTask::class);
    }
}