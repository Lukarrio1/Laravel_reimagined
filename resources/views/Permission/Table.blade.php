<div class="col-sm-8 offset-sm-2 mt-5">
    <div class="card shadow-lg p-3 mb-5 bg-body-tertiary rounded">
        <div class="bg-white card-header">
            Permissions:<span class="badge text-bg-secondary">({{$permissions_count}})</span>
        </div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col" class="text-center">Permission Name</th>
                        <th scope="col" class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($permissions as $Permission )
                    <tr>
                        <td class="text-center">{{$Permission->name}}</td>
                        <td class="text-center">
                            @can('can view permissions edit button',auth()->user())
                            <a href="{{route('editPermission',['permission'=>$Permission])}}" class="btn btn-sm btn-warning m-2">
                                @if(optional($permission)->id==$Permission->id)
                                <i class="fa fa-spinner" aria-hidden="true"></i>
                                @else
                                <i class="fa fa-wrench" aria-hidden="true"></i>
                                @endif

                            </a>
                            @endcan
                            @can('can view permissions delete button', auth()->user())
                            <form action="{{route('deletePermission',['permission'=>$Permission])}}" method="post">
                                @csrf
                                @method('delete')
                                <button class="btn btn-sm btn-danger" type="submit">
                                    <i class="fa fa-trash" aria-hidden="true"></i>
                                </button>
                            </form>
                            @endcan

                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="card-footer bg-white">
            <div class="text-center">
                @include('Components.Pagination',['route_name'=>'viewPermissions'])
            </div>
        </div>

    </div>
</div>
