<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskComment extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'stafftaskcomments';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'content',
        'taskid',
        'staffid',
        'contact_id',
        'file_id',
        'dateadded',
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
}
