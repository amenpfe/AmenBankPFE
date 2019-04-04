@extends('dashboard-template')
@section('content')
    @foreach ($requests as $request)
       {{$request->remarques}} 
    @endforeach   
@endsection