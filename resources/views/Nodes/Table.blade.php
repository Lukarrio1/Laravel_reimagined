<div class="col-lg-12 mt-4">
    <div class="card shadow-lg p-3 mb-5 bg-body-tertiary rounded">
        <div class="card-header bg-white">
            <form action="{{route('viewNodes')}}" action="get">
                <div class="mb-3">
                    <label for="search" class="form-label">
                        Nodes:<span class="badge text-bg-secondary">({{$nodes_count}})</span>
                    </label>
                    <input type="text" class="form-control" id="node_search" name="search" value="{{request('search')}}">
                    <div class="mt-2 text-primary">Example Search Format: {{$search_placeholder}}</div>

                </div>
            </form>
        </div>
        <div class="card-body scrollable-div">
            <table class="table">
                <thead>
                    <tr>

                        <th scope="col" class="text-center h4">Node Name</th>

                        <th scope="col" class="text-center h4">Node Description</th>

                        <th scope="col" class="text-center h4">Node Authentication Level</th>

                        <th scope="col" class="text-center h4">Node Type</th>

                        <th scope="col" class="text-center h4">Node Status</th>

                        <th scope="col" class="text-center h4">Node Permission</th>

                        <th scope="col" class="text-center h4">Node UUID</th>

                        <th scope="col" class="text-center h4">Node Properties</th>

                        <th scope="col" class="text-center h4">Action</th>

                    </tr>
                </thead>
                <tbody>
                    @foreach ($nodes as $Node )
                    <tr>
                        <td><strong>{{$Node->name}}</strong></td>
                        <td>{{$Node->small_description}}</td>
                        <td>{{$Node->authentication_level['human_value']}}</td>
                        <td>{{$Node->node_type['human_value']}}</td>
                        <td>{{$Node->node_status['human_value']}}</td>
                        <td>{{optional(optional($Node)->permission)->name}}</td>
                        <td>{{$Node->uuid}}</td>
                        <td>{!!$Node->properties['html_value']!!}</td>
                        <td>
                            <ul class="list-group list-group-flush">
                                @can('can view nodes edit button', auth()->user())
                                <li class="list-group-item text-center">
                                    <a href="{{route('viewNode',['node'=>$Node])}}" class="btn btn-warning btn-sm m-2 h4" title="edit node">
                                        @if(optional($node)->id==$Node->id)
                                        <i class="fa fa-spinner" aria-hidden="true"></i>
                                        @else
                                        <i class="fa fa-wrench" aria-hidden="true"></i>
                                        @endif

                                    </a>
                                </li>
                                @endcan
                                @can('can view nodes delete button', auth()->user())
                                <li class="list-group-item text-center">
                                    <form action="{{route('deleteNode',['node'=>$Node])}}" method="post">
                                        @method('delete')
                                        @csrf
                                        <button type="submit" class="btn btn-danger btn-sm h4" title="delete node">
                                            <i class="fa fa-trash" aria-hidden="true"></i>
                                        </button>
                                    </form>
                                </li>
                                @endcan

                            </ul>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>


        </div>
        <div class="card-footer bg-white">
            <div class="text-center">
                @include('Components.Pagination',['route_name'=>'viewNodes'])
            </div>
        </div>

    </div>
</div>

@section('scripts')
<script>
    const searchField = document.querySelector('#node_search')
    if (searchField) {
        searchField.addEventListener('input', (e) => {
            localStorage.setitem('node_search', e.target.value)
        })
    }

</script>
@endsection
