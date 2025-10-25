<?php

namespace App\Http\Controllers;

use App\Enums\TodoStatus;
use App\Models\TodoModel;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Requests\StoreTaskRequest;
class TodoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $user =$request->user();
        $userTasks = $user->todoModel();

        if($request->filled('search')){
            $userTasks->where('title','like','%'.$request->input('search').'%');
        }

        if($request->filled('completed')){
            $userTasks->where('completed',$request->input('completed'));
        }
        $tasks = $userTasks->paginate(10)->withQueryString();

        return view('todos.index',[
            'tasks' => $tasks,
            'request' => $request,
            'statuses'=>TodoStatus::cases()
        ]);


    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('todos.create',[
            'statuses'=>TodoStatus::cases()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([]);
        if ($request->user()->tasks()->create($validatedData)) {
            return redirect()->route('todos.index')->with('success', 'The task added successfully');
        }else{
            return redirect()->route('todos.index')->with('error', 'something went wrong');

        }

    }

    /**
     * Display the specified resource.
     */
    public function view(TodoModel $todoModel)
    {
        return view('todos.view',[
            'todoModel' => $todoModel,
            'statuses'=>TodoStatus::cases()
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TodoModel $todoModel)
    {
         return view('todos.update',[
            'todoModel' => $todoModel,
            'statuses'=>TodoStatus::cases()
        ]);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TodoModel $todoModel)
    {
        $this->authorize('update',$todoModel);
        $validatedData = $request->validate([]);
        $todoModel->update($validatedData);
        return redirect()->route('todos.index')->with('success', 'The task updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TodoModel $todoModel)
    {
        $this->authorize('delete',$todoModel);
        $todoModel->delete();
        return redirect()->route('todos.index')->with('success', 'The task deleted successfully');
    }
}
