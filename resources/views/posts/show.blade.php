@extends('layouts.app')

@section('content')
    @if ($user->profile)
        <p class="text-white">الهاتف: {{ $user->profile->photo }}</p>
    @else
        <p>لا يوجد ملف شخصي</p>
    @endif

    <hr>

    <h3 class="text-white"> منشورات {{ $user->name }}: ({{ $user->posts->count() }} منشورات)</h3>

    @forelse ($user->posts as $post)
        <div class="card mb-3 text-cyan-50">
            <div class="card-body">
                <h5 class="card-title text-white">{{ $post->title }}</h5>
                <p class="card-text text-white">{{ Str::limit($post->body, 150) }}</p>
                <small class="text-muted text-white">نُشر في: {{ $post->created_at->format('Y-m-d') }}</small>
            </div>
        </div>
    @empty
        <p class="text-muted text-white">هذا المستخدم لم يكتب أي منشورات بعد.</p>
    @endforelse

@endsection
