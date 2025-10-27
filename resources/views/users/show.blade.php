@extends('layouts.app')

@section('content')
    <h1 class="text-white">{{ $user->name }}</h1>
    <p class="text-white">Email: {{ $user->email }}</p>

    <hr class="text-white">

    <h2 class="text-white">الملف الشخصي:</h2>

    @if ($user->profile)
        <p class="text-white"><strong>الهاتف:</strong> {{ $user->profile->photo }}</p>
        <p class="text-white"><strong>العنوان:</strong> {{ $user->profile->address }}</p>
        <p class="text-white"><strong>السيرة الذاتية:</strong> {{ $user->profile->bio }}</p>
    @else
        <p class="text-muted text-cyan-50">هذا المستخدم لم يقم بإضافة ملف شخصي بعد.</p>
    @endif

    <p class="text-white">  الهاتف (بطريقة آمنة): {{ optional($user->profile)->phone ?? 'لا يوجد هاتف' }}</p>
@endsection
