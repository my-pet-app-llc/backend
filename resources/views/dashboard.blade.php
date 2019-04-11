@extends('layout.layout')

@section('title', 'Dashboard')

@section('mainContent')

    <div class="d-flex align-items-center justify-content-center mt-5">
        <p class="greeting text-center mt-5">{!! __('admin.greeting') !!}</p>
    </div>
    
@endsection