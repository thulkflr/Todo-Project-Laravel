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

        $user = $request->user();
        $status = $request->query('completed');

        $q = $request->query('q');
        $query = TodoModel::where('user_id', $user->id);

        if ($status && in_array($status, [TodoStatus::COMPLETED_TASK, TodoStatus::PENDING_TASK])) {
        }
        {
            $query->where('completed', $status);
        }
        if ($q) {
            $query->where('title', 'like', "%$q%");
        }
        $userTasks = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();


        return view('todos.index', [
            'tasks' => $userTasks,
            'statuses' => $status,
            'q' => $q,
        ]);


    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('todos.create', [
            'statuses' => TodoStatus::cases()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([]);
        $validatedData['user_id'] = $request->user()->id;
        TodoModel::create($validatedData);

        if ($request->user()->tasks()->create($validatedData)) {
            return redirect()->route('todos.index')->with('success', 'The task added successfully');
        } else {
            return redirect()->route('todos.index')->with('error', 'something went wrong');

        }

    }

    /**
     * Display the specified resource.
     */
    public function show(TodoModel $todoModel)
    {
        $this->authorize('view', $todoModel);
        return view('todos.view', [
            'todoModel' => $todoModel,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TodoModel $todoModel)
    {
        $this->authorize('update', $todoModel);

        return view('todos.update', [
            'todoModel' => $todoModel,
        ]);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TodoModel $todoModel)
    {
        $this->authorize('update', $todoModel);
        $validatedData = $request->validate([]);
        $todoModel->update($validatedData);
        return redirect()->route('todos.index')->with('success', 'The task updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TodoModel $todoModel)
    {
        $this->authorize('delete', $todoModel);
        $todoModel->delete();
        return redirect()->route('todos.index')->with('success', 'The task deleted successfully');
    }
}
