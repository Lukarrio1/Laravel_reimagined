<div class="col-sm-8 offset-sm-2 mt-3">
    <div class="card shadow-lg p-3 mb-5 bg-body-tertiary rounded">

        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col" class="text-center">Role Name</th>
                        <th scope="col" class="text-center">Role Permissions</th>
                        <th scope="col" class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($roles as $role )
                    <tr>
                        <td class="text-center">{{$role['name']}}</td>

                        <td class="text-center">
                            <ul class="list-group-flush">
                                @foreach ($role['permission_name'] as $name )
                                <li class="list-group-item">{{$name}}</li>
                                @endforeach
                            </ul>
                        </td>
                        <td class="text-center">
                            <a href="{{route('editRole',['role'=>$role['id']])}}" class="btn btn-sm btn-warning m-2">
                                <i class="fa fa-wrench" aria-hidden="true"></i>
                            </a>
                            <form action="{{route('deleteRole',['role'=>$role['id']])}}" method="post">
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
    </div>
</div>
