

@extends('Layouts.layout')
@section('content')
<div class="row">
    @include('Permission.Create')
    @include('Permission.Table')
</div>
@endsection


