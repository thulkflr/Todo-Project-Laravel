@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>My Tasks</h2>
            <a href="{{ route('todos.create') }}" class="btn btn-primary">Create Task</a>
        </div>

        {{-- رسائل النجاح / الخطأ --}}
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        {{-- فلترة وبحث --}}
        <form method="GET" class="row g-2 mb-3">
            <div class="col-md-4">
                <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Search by title...">
            </div>
            <div class="col-md-3">
                <select name="status" class="form-control">
                    <option value="">-- All statuses --</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                </select>
            </div>
            <div class="col-md-2">
                <button class="btn btn-secondary">Filter</button>
            </div>
        </form>

        @if($tasks->count())
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>Title</th>
                    <th>Due</th>
                    <th>Status</th>
                    <th>Attachment</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach($tasks as $task)
                    <tr>
                        <td>
                            <a href="{{ route('todos.show', $task) }}">{{ $task->title }}</a>
                        </td>
                        <td>{{ $task->due_date ? $task->due_date->format('Y-m-d') : '-' }}</td>
                        <td>
                            @if($task->isCompleted())
                                <span class="badge bg-success">Completed</span>
                            @else
                                <span class="badge bg-warning text-dark">Pending</span>
                            @endif
                        </td>
                        <td>
                            @if($task->attachment)
                                <a href="{{ asset('storage/'.$task->attachment) }}" target="_blank">View</a>
                            @else
                                -
                            @endif
                        </td>
                        <td class="text-end">
                            <a href="{{ route('todos.edit', $task) }}" class="btn btn-sm btn-outline-primary">Edit</a>

                            <form action="{{ route('todos.destroy', $task) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Delete this task?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            {{ $tasks->links() }} {{-- pagination links --}}
        @else
            <div class="alert alert-info">No tasks yet. Create one!</div>
        @endif
    </div>
@endsection
