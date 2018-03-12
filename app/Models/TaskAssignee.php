<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskAssignee extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'stafftaskassignees';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'staffid',
        'taskid',
        'assigned_from',
        'is_assigned_from_contact',
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
}
