@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>{{ $task->title }}</h2>

        <p><strong>Status:</strong>
            @if($task->isCompleted())
                <span class="badge bg-success">Completed</span>
            @else
                <span class="badge bg-warning text-dark">Pending</span>
            @endif
        </p>

        <p><strong>Due:</strong> {{ $task->due_date?->format('Y-m-d') ?? '-' }}</p>

        <p><strong>Description:</strong></p>
        <p>{{ $task->description ?? '-' }}</p>

        @if($task->attachment)
            <p><strong>Attachment:</strong> <a href="{{ asset('storage/'.$task->attachment) }}" target="_blank">Download/View</a></p>
        @endif

        <a href="{{ route('tasks.edit', $task) }}" class="btn btn-primary">Edit</a>
        <a href="{{ route('tasks.index') }}" class="btn btn-secondary">Back</a>
    </div>
@endsection
