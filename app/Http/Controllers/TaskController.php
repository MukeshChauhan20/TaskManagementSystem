<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use App\Models\TaskUser;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class TaskController extends Controller
{
    public function getList(){
        request()->validate([
            'status' => 'nullable|in:'.implode(',',Task::status),
            'assinged_user' => 'nullable|integer',
        ]);

        $data = Task::when(!empty(request('status')) && in_array(request('status'),Task::status),function($query){
                    $query->where('status',request('status'));
                })->when(!empty(request('due_date')), function($query){
                    $query->where('due_date',Carbon::parse(request('due_date'))->toDateString());
                })->get();
        
        
        if(!empty(request('assinged_user'))){
            $data = $data->map(function($item){
                if($item->assignedUsers()->where('user_id',request('assinged_user'))->count() > 0){
                    return $item;
                }
            });
        }

        return response()->json([
            'status' => $data->count() > 0 ? true : false,
            'message' => $data->count() > 0 ? $data->count().' has been found' : 'No Record found',
            'data' => array_filter($data->toArray())
        ]);
    }

    public function create(){
        request()->validate([
            'title' => 'required',
            'descriptions'  => 'required',
            'due_date' => 'required|date_format:Y-m-d',
            'status' => 'required|in:'.implode(',',Task::status),
        ]);

        $task = Task::create(request()->all());

        return response()->json([
            'status' => true,
            'message' => 'Task created successfully',
            'id' => $task->getKey()
        ]);
    }

    public function update(Task $task){
        request()->validate([
            'title' => 'required',
            'descriptions'  => 'required',
            'due_date' => 'required|date_format:Y-m-d',
            'status' => 'required|in:'.implode(',',Task::status),
        ]);

        $task->title = request()->title;
        $task->descriptions = request()->descriptions;
        $task->due_date = request()->due_date;
        $task->status = request()->status;

        $task->save();

        return response()->json([
            'status' => true,
            'message' => 'Task Update Successfully'
        ]);
    }

    public function delete(Task $task){
        $task->delete();
        
        return response()->json([
            'status' => true,
            'message' => 'Task Deleted successfully',
        ]);
    }

    public function assignTaskToUser(){
        request()->validate([
            'task_id' => 'required|numeric',
            'user_id' => 'required|array'
        ]);

        $task = Task::find(request('task_id'));
        
        if(empty($task)){
            return response()->json([
                'status' => false,
                'message' => 'Task not found',
            ],404);
        }

        TaskUser::where('task_id',request('task_id'))->delete();

        $task->assignedUsers()->attach(request('user_id'));

        return response()->json([
            'status' => true,
            'message' => 'Task assigned successfully'
        ]);
    }

    public function getAssignedTask(){
        request()->validate([
            'user_id' => 'nullable|exists:App\Models\User,id'
        ]);

        if(!empty(request('user_id'))){
            $data = User::find(request('user_id'))->assignedTask();
        }else{
            $data = auth()->user()->assignedTask();
        }

        return response()->json([
            'status' => $data->count() > 0 ,
            'data' => $data->count() > 0 ? $data->get() : [],
            'message' => $data->count() > 0 ? 'Record fetched successfully' : 'No Record Found'
        ]);
    }

    public function unassignUserFromTask(){
        request()->validate([
            'task_id' => 'required|numeric',
            'user_id' => 'required|numeric'
        ]);

        $taskUser = TaskUser::where(['task_id' => request('task_id'),'user_id' => request('user_id')]);

        if($taskUser->count() == 0){
            return response()->json([
                'status' => false,
                'message' => 'Invalid Task No Or User ID'
            ]);
        }

        $taskUser->delete();

        return response()->json([
            'status' => true,
            'message' => 'Assignment remove successfully by User Id'
        ]);
    }

}
