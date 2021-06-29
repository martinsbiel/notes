@extends('layouts.app')

@section('content')
    <search-component user_id="{{auth()->user()->id}}"></search-component>
@endsection
