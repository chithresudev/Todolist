<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\SubTask;
use Carbon\Carbon;
use App\Http\Requests\TaskRequest;
use Illuminate\Http\Request;

class TaskController extends Controller
{

    /**
     * Show all task except completed task.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

      $task = Task::pending();

      // Search task based on title
      if ($request->search && $request->value) {
        $task = $task->where('title', 'like', '%' . $request->value . '%');
      }

      // Filter task based on Carbon
      if ($request->filter) {
              $now = Carbon::now();
              // This week
            if ($request->filter == 'this_week') {
              $from = $now->startOfWeek()->format('Y-m-d H:i');
              $end = Carbon::parse($from)->endOfWeek()->format('Y-m-d H:i');
            }
            // Next week
            else if ($request->filter == 'next_week') {
              $from = $now->addWeek(1)->startOfDay()->format('Y-m-d H:i');
              $end = Carbon::parse($from)->endOfWeek()->format('Y-m-d H:i');
            }
            // Today
            else {
              $from = $now->startOfDay()->format('Y-m-d H:i');
              $end = Carbon::parse($from)->endOfDay()->format('Y-m-d H:i');
            }

            $task = $task->whereBetween('due_date', [$from, $end]);
      }

        $tasks = $task->get();
        if (count($tasks)) {

            $response = [
              'success' => true,
              'data' => $tasks,
              'message' => 'Task Data Retrived Successfully',
          ];

          return response()->json($response, 200);
        }

        return response()->json(['message' => 'No Task Available'], 200);
    }

    /**
     * Create a new task.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function createTask(TaskRequest $request)
    {
        $task = new Task();
        $task->title = $request->title;
        $task->description = $request->description;
        $task->due_date = $request->due_date;
        $task->save();

          $response = [
            'success' => true,
            'data' => $task,
            'message' => 'Task Created Successfully',
        ];

        return response()->json($response, 201);
    }

    /**
     * Create a new subtask based on main task.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function createSubTask(Task $task, TaskRequest $request)
    {

        $sub_task = new SubTask();
        $sub_task->task_id = $task->id;
        $sub_task->title = $request->title;
        $sub_task->description = $request->description;
        $sub_task->due_date = $request->due_date;
        $sub_task->save();

        $response = [
          'success' => true,
          'data' => $sub_task,
          'message' => 'Sub Task Created Successfully',
      ];

    return response()->json($response, 201);
    }

    /**
     * Task states updates also sub task states is changed.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function statesUpdate(Task $task, Request $request)
    {
      $task->states = $request->states;
      $task->save();
      foreach ($task->subtasks as $key => $sub_task) {
        $sub_task->states = $task->states;
        $sub_task->save();
      }

        $response = [
          'success' => true,
          'data' => $task,
          'message' => 'Task States Updated',
      ];

      return response()->json($response, 201);
    }

    /**
     * Remove the task softDeletes.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function taskRemove(Task $task)
    {
        $task->delete();
        $response = [
          'success' => true,
          'message' => 'Removed the Task',
      ];
      return response()->json($response, 201);
    }
}
