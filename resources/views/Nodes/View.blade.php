@extends('Layouts.app')
@section('content')
<div class="row">
    @can('can view nodes edit or create form', auth()->user())
    @include('Nodes.Create')
    @endcan
    @can('can view nodes data table', auth()->user())
    @include('Nodes.Table')
    @endcan

</div>

@endsection
