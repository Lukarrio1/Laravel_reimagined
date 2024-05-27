<div class="col-lg-12 mt-4">
    <div class="card shadow-lg p-3 mb-5 bg-body-tertiary rounded">
        <div class="card-header bg-white">
            <form action="{{route('viewNodes')}}" action="get">
                <div class="mb-3">
                    <label for="search" class="form-label">
                        Nodes:<span class="badge text-bg-secondary">({{count($nodes)}})</span>
                    </label>
                    <input type="text" class="form-control" id="search" name="search" value="{{request('search')}}">
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
                    @foreach ($nodes as $node )
                    <tr>
                        <td><strong>{{$node->name}}</strong></td>
                        <td>{{$node->small_description}}</td>
                        <td>{{$node->authentication_level['human_value']}}</td>
                        <td>{{$node->node_type['human_value']}}</td>
                        <td>{{$node->node_status['human_value']}}</td>
                        <td>{{optional(optional($node)->permission)->name}}</td>
                        <td>{{$node->uuid}}</td>
                        <td>{!!$node->properties['html_value']!!}</td>
                        <td>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item text-center">
                                    <a href="{{route('viewNode',['node'=>$node])}}" class="btn btn-warning btn-sm m-2 h4" title="edit node">
                                        <i class="fa fa-wrench" aria-hidden="true"></i>
                                    </a>
                                </li>
                                <li class="list-group-item text-center">
                                    <form action="{{route('deleteNode',['node'=>$node])}}" method="post">
                                        @method('delete')
                                        @csrf
                                        <button type="submit" class="btn btn-danger btn-sm h4" title="delete node">
                                            <i class="fa fa-trash" aria-hidden="true"></i>
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>


        </div>
        <div class="card-footer bg-white">
            <div class="text-center">
                <a class="btn btn-sm btn-primary" href="{{route('viewNodes').'?load_more='.request()->get('load_more')+10}}">load more</a>

            </div>
        </div>

    </div>
</div>
