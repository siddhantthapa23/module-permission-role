@extends('backend.container')

@section('content')
Welcome to your dashboard.

<div>
    <a href="{{ route('logout') }}" class="btn btn-default btn-flat"
        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
        Logout
    </a>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        {{ csrf_field() }}
    </form>
</div>
@endsection