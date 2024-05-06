<div class="col-sm-8 offset-sm-2 mt-5">
    <div class="card">
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

                        <td class="text-center">{{$role['permission_name']}}</td>

                        <td class="text-center">
                            <a href="{{route('editRole',['role'=>$role['id']])}}" class="btn btn-sm btn-warning">
                                edit
                            </a>
                            <form action="{{route('deleteRole',['role'=>$role['id']])}}" method="post">
                                @csrf
                                @method('delete')
                                <button class="btn btn-sm btn-danger" type="submit">
                                    delete
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
