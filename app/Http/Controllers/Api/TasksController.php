<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\TaskRequest;
use App\Task;
use App\Http\Transformers\v1\TaskTransformer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TasksController extends Controller
{

    private $task;

    public function __construct(Task $task)
    {
        $this->task = $task;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $tasks = $this->task->filter($request->all())->paginate($request->get('per_page', 10));
        return $this->response->paginator($tasks, new TaskTransformer);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TaskRequest $request)
    {
        $task = $this->task->create($request->all());
        return $this->response->item($task, new TaskTransformer);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $task = $this->task->findOrFail($id);
        return $this->response->item($task, new TaskTransformer);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(TaskRequest $request, $id)
    {
        $task = $this->task->findOrFail($id);
        $task->update($request->except('tags'));
        $task->syncTags($request->get('tags'));
        return $this->response->item($task, new TaskTransformer);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $task = $this->task->findOrFail($id);
        $task->delete();
        return $this->response()->noContent();

    }
}
