<div class="col-sm-8 offset-sm-2 mt-5">
    <div class="card shadow-lg p-3 mb-5 bg-body-tertiary rounded">
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
                                edit
                            </a>
                            <form action="{{route('deletePermission',['permission'=>$permission])}}" method="post">
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

