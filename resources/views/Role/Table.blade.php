<div class="col-sm-8 offset-sm-2 mt-3">
    <div class="card shadow-lg p-3 mb-5 bg-body-tertiary rounded">
        <div class="card-header bg-white h6">
            <form action="{{route('viewRoles')}}" action="get">
                <div class="mb-3">
                    <label for="search" class="form-label h3">
                        <span class="badge text-bg-secondary"> Roles: ({{!empty($search)?$roles_count.'/'.$roles_count_overall:$roles_count}})</span>
                    </label>
                    <input type="text" class="form-control" name="search" value="{{$search}}" placeholder="Search...">
                    <div class="mt-2 text-primary">Example Search Format:{{$searchPlaceholder}}</div>
                </div>
            </form>

        </div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col" class="text-center">Role Name</th>
                        <th scope="col" class="text-center">Role Priority</th>
                        <th scope="col" class="text-center">Role Permissions</th>
                        <th scope="col" class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($roles as $Role )
                    <tr>
                        <td class="text-center">{{$Role['name']}}</td>
                        <td class="text-center">{{$Role['priority']}}</td>
                        <td class="text-center">
                            <ul class="list-group-flush">
                                @foreach ($Role['permission_name'] as $name )
                                <li class="list-group-item">
                                    <bold>{{$name}}</bold>
                                </li>
                                @endforeach
                            </ul>
                        </td>
                        <td class="text-center">

                            @can('can view roles edit button', auth()->user())
                            <a href="{{route('editRole',['role'=>$Role['id']])}}" class="btn btn-sm btn-warning m-2">
                                @if(optional($role)->id==$Role['id'])
                                <i class="fa fa-spinner" aria-hidden="true"></i>
                                @else
                                <i class="fa fa-wrench" aria-hidden="true"></i>
                                @endif
                            </a>
                            @endcan
                            @if($Role['core']!=1)
                            @can('can view roles delete button', auth()->user())
                            <form action="{{route('deleteRole',['role'=>$Role['id']])}}" method="post">
                                @csrf
                                @method('delete')
                                <button class="btn btn-sm btn-danger" type="submit">
                                    <i class="fa fa-trash" aria-hidden="true"></i>
                                </button>
                            </form>
                            @endcan
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="card-footer bg-white">
            <div class="text-center">

                @include('Components.Pagination',['route_name'=>'viewRoles'])

                {{-- <a class="btn btn-sm btn-primary" href="{{route('viewNodes').'?page='.request()->get('page')+10}}">load more</a> --}}
            </div>

        </div>
    </div>
</div>
