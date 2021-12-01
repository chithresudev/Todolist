<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\SubTask;
use Carbon\Carbon;
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

      $task = Task::with('subtasks')->whereIn('states', ['pending'])->orderBy('due_date', 'asc');

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

        $task = $task->get();
        if (count($task)) {
          return response()->json($task);
        }

        return response()->json('No Task Available');
    }

    /**
     * Create a new task.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function createTask(Request $request)
    {
      $task = new Task();
      $task->title = $request->title;
      $task->description = $request->description;
      $task->due_date = $request->due_date;
      $task->save();

      return response()->json($task);
    }

    /**
     * Create a new subtask based on main task.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function createSubTask(Task $task, Request $request)
    {

      $sub_task = new SubTask();
      $sub_task->task_id = $task->id;
      $sub_task->title = $request->title;
      $sub_task->description = $request->description;
      $sub_task->due_date = $request->due_date;
      $sub_task->save();

      return response()->json($sub_task);
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

      return response()->json($task);
    }

    /**
     * Task states updates also sub task states is changed.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function taskRemove(Task $task)
    {
      $task->delete();
      return response()->json('Removed the Task');
    }
}
