<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Frequency extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'label', 'days_interval'];

    // If you need to access tasks that use this as default frequency
    public function tasks()
    {
        return $this->hasMany(Task::class, 'default_frequency_id');
    }

    // If you need to access area_tasks that use this as custom frequency
    public function areaTasks()
    {
        return $this->hasMany(AreaTask::class, 'custom_frequency_id');
    }
}