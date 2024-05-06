<div class="col-sm-12 mt-4">
    <div class="card">
    <div class="card-header">
        <form action="{{route('viewNodes')}}" action="get">
        <div class="mb-3">
            <label for="search" class="form-label">Node Name ({{count($nodes)}})</label>
            <input type="text" class="form-control" id="search" name="search" value="{{request('search')}}">
            Search format: {{$search_placeholder}}
        </div>
        </form>
    </div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">Node Name</th>
                        <th scope="col">Node Description</th>
                        <th scope="col">Node Authentication Level</th>
                        <th scope="col">Node Type</th>
                        <th scope="col">Node Status</th>
                        <th scope="col">Node Permission</th>
                        <th scope="col">Node Properties</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($nodes as $node )
                    <tr>
                        <td>{{$node->name}}</td>
                        <td>{{$node->small_description}}</td>
                        <td>{{$node->authentication_level['human_value']}}</td>
                        <td>{{$node->node_type['human_value']}}</td>
                        <td>{{$node->node_status['human_value']}}</td>
                        <td>{{optional(optional($node)->permission)->name}}</td>
                        <td>{!!$node->properties['html_value']!!}</td>
                        <td>
                        <a href="{{route('viewNode',['node'=>$node])}}" class="btn btn-warning btn-sm">Edit</a>
                     <form action="{{route('deleteNode',['node'=>$node])}}" method="post">
                     @method('delete')
                     @csrf
                        <button type="submit" class="btn btn-danger btn-sm">Delete</button></td>
                     </form>
                    </tr>
                    @endforeach
                </tbody>
            </table>


        </div>
    </div>
</div>
