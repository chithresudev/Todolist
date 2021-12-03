<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{

    use HasFactory, SoftDeletes;

    protected $dates = [ 'deleted_at' ];
    protected $appends = [ 'subtasks' ];

    /**
     * Get the sub task for the tasks.
     */
    public function subtasks()
    {
        return $this->hasMany(SubTask::class);
    }

    /**
     * Get the sub task for the tasks.
     */
    public function getSubtasksAttribute()
    {
        return $this->subtasks()->get();
    }
    /**
     * Get the pending task in scope values.
     */
    public function scopePending($query)
    {
        return $query->whereIn('states', ['pending'])->orderBy('due_date', 'asc');
    }
}
