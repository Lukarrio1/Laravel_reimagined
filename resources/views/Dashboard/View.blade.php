@extends('Layouts.app')
@section('content')
<div class="row">

    @can("can view new users dashboard component",auth()->user())
    @include('Dashboard.NewUser')
    @endcan
    @can("can view last update api route dashboard component",auth()->user())
    @include('Dashboard.LastUsedRoute')
    @endcan
    @can("can view audit history dashboard component",auth()->user())
    @include('Dashboard.AuditHistory')
    @endcan

</div>
@endsection
