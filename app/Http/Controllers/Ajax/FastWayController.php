<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use App\Repositories\TaskRepository;
use App\Http\Requests\FastWay\TaskRequest;

class FastWayController extends Controller
{
    /**
     * Instância da classe TaskRepository.
     *
     * @var TaskRepository
     */
    protected $taskRepository;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(TaskRepository $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

    /**
     * Cria uma nova tarefa.
     *
     * @param  TaskRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function new(TaskRequest $request)
    {
        $task = $this->taskRepository->create($request->all());

        return response()->json($request->all());
    }
}
