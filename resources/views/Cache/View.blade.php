@extends('Layouts.app')
@section('content')
<div class="col-sm-8 offset-sm-2 mt-2">
<div class="card">
    <div class="card-body">
        <div class="text-center">
            <a class="btn-warning btn" href="{{route('clearCache')}}">CLEAR CACHE</a>
        </div>
    </div>
</div>
</div>
@endsection