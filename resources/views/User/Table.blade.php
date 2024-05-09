<div class="col-sm-8 offset-sm-2">
    <div class="card">
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Fullname</th>
                        <th scope="col">Email</th>
                        <th scope="col">Role</th>
                        <th scope="col" class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $key=>$user )
                    <tr>
                        <th scope="row">{{$key}}</th>
                        <td>{{$user->name}}</td>
                        <td>{{$user->email}}</td>
                        <td>{{$user->role_name}}</td>
                        <td class="text-center">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#assignRoleModal{{$user->id}}">
                                Assign Role
                            </button>
                            <button type="button" class="btn btn-warning user_edit_button" data-bs-toggle="modal" data-bs-target="#editUserModal" data-user-id="{{$user->id}}">
                                Edit
                            </button>
                            <a class="btn btn-sm btn-danger" href="">Delete</a>
                        </td>
                    </tr>
                    <div class="modal fade" id="assignRoleModal{{$user->id}}" tabindex="-1" aria-labelledby="assignRoleModalLabel" aria-hidden="true">

                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="assignRoleModalLabel">Assign Role To {{$user->name}}</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form action="{{route('assignRole',['user'=>$user])}}" method="post">
                                        @csrf
                                        <div class="mb-3">
                                            <label for="role_name" class="form-label">Role</label>
                                            <select class="form-select" name="role">
                                                <option selected>Open this select menu</option>
                                                @foreach ($roles as $role )
                                                <option value="{{$role->id}}" {{optional($user->role)->id==$role->id?"selected":''}}>
                                                    {{$role->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="mt-2 text-center">
                                            <button type="submit" class="btn btn-primary">Submit</button>
                                        </div>

                                    </form>

                                </div>
                                {{-- <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="button" class="btn btn-primary">Save changes</button>
                                </div> --}}
                            </div>
                        </div>
                    </div>


                    @endforeach
                </tbody>
            </table>

        </div>
          <div class="modal fade " id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">

              <div class="modal-dialog">
                  <div class="modal-content">
                      <div class="modal-header">
                          <h1 class="modal-title fs-5" id="assignRoleModalLabel">Update</h1>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body">
                          <form action="{{route('updateUser',['user'=>1])}}" method="post">
                              @csrf
                              <div id="custom_input_user_fields"></div>
                              <div class="mt-2 text-center">
                                  <button type="submit" class="btn btn-primary">Submit</button>
                              </div>
                          </form>

                      </div>
                      {{-- <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="button" class="btn btn-primary">Save changes</button>
                                </div> --}}
                  </div>
              </div>
          </div>

    </div>
</div>
@section('scripts')
<script>
    const users = @json($users)

    const allEditBtns = document.querySelectorAll('.user_edit_button')
        if(allEditBtns){
            allEditBtns.forEach(btn=>{
                btn.addEventListener('click',(e)=>{
                const current_user = users.filter(user=>user?.id==btn.getAttribute('data-user-id'))[0]
                document.querySelector('#custom_input_user_fields').innerHTML= current_user.updateHtml
                console.log(current_user)
                })

            })

        }
    console.log(users)

</script>

@endsection