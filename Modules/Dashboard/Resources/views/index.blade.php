@extends('backend.container')

@section('content')
    <h3>Welcome {{ auth()->user()->full_name }}.</h3>
@endsection