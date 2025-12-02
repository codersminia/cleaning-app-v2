<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProjectTask extends Model
{
    use HasFactory;
    protected $fillable = ['project_id','task_id','task_description'];

        public function task()
    {
        return $this->belongsTo(Task::class, 'task_id');
    }
}
