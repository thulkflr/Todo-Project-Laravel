@extends('layouts.app')
@section('content')

    <table class="table-auto md:table-fixed text-white">
        <thead>
        <tr>
            <th>
                name
            </th>
            <th>
                phone number
            </th>
            <th>
                bio
            </th>
            <th>
                address
            </th>
        </tr>
        </thead>
        <tbody>
        @foreach($users as $user)
            <tr>
                <td>
                    {{$user->name}}
                </td>
                @if ($user->profile)
                    <td>
                        {{$user->profile->phone}} </td>
                    <td>
                        {{$user->profile->bio}}
                    </td>
                    <td>
                        {{$user->profile->address}}
                    </td>
                @else
                    <td colspan="3" class="text-muted">this user doesnt have a profile</td>
                @endif
            </tr>
        </tbody>
        @endforeach
    </table>
@endsection
