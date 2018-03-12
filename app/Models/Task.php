<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    /**
     * Código do usuário no sistema da FastWay.
     */
    const USER_ID = 16;

    /**
     * Valores imutáveis das tarefas.
     *
     * @var array
     */
    public $defaultValues = [
        'datefinished' => '0000-00-00 00:00:00',
        'status'       => 1,
        'addedfrom'    => self::USER_ID,
        'priority'     => 2,
        'repeat_every' => 0,
        'billable'     => 1,
        'hourly_rate'  => 25,
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'stafftasks';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'priority',
        'dateadded',
        'startdate',
        'duedate',
        'datefinished',
        'addedfrom',
        'is_added_from_contact',
        'status',
        'recurring_type',
        'repeat_every',
        'recurring',
        'recurring_ends_on',
        'custom_recurring',
        'last_recurring_date',
        'rel_id',
        'rel_type',
        'is_public',
        'billable',
        'billed',
        'invoice_id',
        'hourly_rate',
        'milestone',
        'kanban_order',
        'milestone_order',
        'visible_to_client',
        'deadline_notified',
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Pessoa responsável pela tarefa.
     *
     * @return TaskAssignee
     */
    public function assignee()
    {
        return $this->hasOne(TaskAssignee::class, 'taskid');
    }

    /**
     * Comentários da tarefa.
     *
     * @return TaskComment
     */
    public function comments()
    {
        return $this->hasMany(TaskComment::class, 'taskid');
    }
}
