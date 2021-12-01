<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{

    use HasFactory, SoftDeletes;

    protected $dates = [ 'deleted_at' ];

    /**
     * Get the sub task for the tasks.
     */
    public function subtasks()
    {
        return $this->hasMany(SubTask::class);
    }
}
