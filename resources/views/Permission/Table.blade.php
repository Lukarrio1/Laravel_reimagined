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
                    @foreach ($permissions as $permission )
                    <tr>
                        <td class="text-center">{{$permission->name}}</td>
                        <td class="text-center">
                            <a href="{{route('editPermission',['permission'=>$permission])}}" class="btn btn-sm btn-warning m-2">
                                <i class="fa fa-wrench" aria-hidden="true"></i>
                            </a>
                            <form action="{{route('deletePermission',['permission'=>$permission])}}" method="post">
                                @csrf
                                @method('delete')
                                <button class="btn btn-sm btn-danger" type="submit">
                                    <i class="fa fa-trash" aria-hidden="true"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="card-footer bg-white">
            <div class="text-center">
                <nav aria-label="Page navigation" class="mt-5">
                    <ul class="pagination justify-content-center">
                        <!-- Previous Page Link -->
                        <li class="page-item">
                            <a class="page-link" href="{{route('viewPermissions').'?page='.request()->get('page')-1}}" aria-label="Previous">
                                <span aria-hidden="true">&laquo; Previous</span>
                            </a>
                        </li>
                        <li class="page-item">
                            <a class="page-link" href="!#" aria-label="Previous">
                                <span aria-hidden="true">{{request()->get('page')}} </span>
                            </a>
                        </li>
                        <li class="page-item">
                            <a class="page-link" href="{{route('viewPermissions').'?page='.request()->get('page')+1}}" aria-label="Next">
                                <span aria-hidden="true">Next &raquo;</span>
                            </a>
                        </li>
                    </ul>
                </nav>

                {{-- <a class="btn btn-sm btn-primary" href="{{route('viewNodes').'?page='.request()->get('page')+10}}">load more</a> --}}

            </div>
        </div>

    </div>
</div>
