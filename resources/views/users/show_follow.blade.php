@extends('layouts.default')
@section('title', $follower ? '粉丝' : '关注的人')

@section('content')
    <div class="col-md-offset-2 col-md-8">
        <h1>{{ $follower ? '粉丝' : '关注的人' }}</h1>
        <ul class="users">
            @foreach ($users as $user)
                <li>
                    <div class="row">
                        <div class="col-md-8">
                            <img src="{{ $user->gravatar() }}" alt="{{ $user->name }}" class="gravatar"/>
                            <a href="{{ route('users.show', $user->id )}}" class="username">{{ $user->name }}</a>
                        </div>
                        @if (!$follower)
                            <div class="col-md-4 text-right">
                                <form action="{{ route('followers.destroy', $user->id) }}" method="post">
                                    {{ csrf_field() }}
                                    {{ method_field('DELETE') }}
                                    <button type="submit" class="btn btn-sm">取消关注</button>
                                </form>
                            </div>
                        @endif
                    </div>
                </li>
            @endforeach
        </ul>

        {!! $users->render() !!}
    </div>
@stop