@extends('layouts.app')

@section('content')
    <notes-component user_id="{{auth()->user()->id}}"></notes-component>
@endsection
