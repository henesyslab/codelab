<?php

namespace App\Repositories;

use App\Models\Task;
use Illuminate\Support\Facades\DB;

class TaskRepository extends BaseRepository
{
    /**
     * Construtor da classe.
     *
     * @param  Task  $task
     * @return void
     */
    public function __construct(Task $task)
    {
        $this->model = $task;
    }

    /**
     * Retorna todos os registros do banco de dados.
     *
     * @param  array  $columns
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function fetchAll($columns = ['*'])
    {
        $table = $this->model->getTable();

        $columns = collect($columns)->map(function($column) {
            return 'table.'.$column;
        })->all();

        return $this->model
                ->from("$table as table")
                ->select($columns)
                ->leftJoin('task_assigned as ta', 'table.id', '=', 'ta.taskid')
                ->where('ta.staffid', $this->model::USER_ID)
                ->get();
    }

    /**
     * Cria um novo registro com os dados recebidos.
     *
     * @param  array  $attributes
     * @return Model
     */
    public function create(array $attributes)
    {
        $data = array_merge($attributes, $this->model->defaultValues, [
            'dateadded' => date('Y-m-d H:i:s'),
            'startdate' => date('Y-m-d'),
            'duedate' => date('Y-m-d', strtotime('+3 days')),
            'deadline_notified' => 1,
        ]);

        $this->model->fill($data);
        $this->model->save();

        // Criação dos metadados da tarefa
        $this->taskMetadata($this->model->id);

        return $this->model;
    }

    /**
     * Cria os metadados de uma determinada tarefa.
     *
     * @param  int  $id
     * @return void
     */
    protected function taskMetadata(int $id)
    {
        DB::table('task_assigned')->insert([
            'staffid' => $this->model::USER_ID,
            'taskid' => $id,
            'assigned_from' => $this->model::USER_ID,
        ]);

        DB::table('task_followers')->insert([
            'staffid' => 1,
            'taskid' => $id,
        ]);

        DB::table('taggables')->insert([
            'rel_id' => $id,
            'rel_type' => 'task',
            'tag_id' => 8,
        ]);
    }
}
