<div class="col-sm-8 offset-sm-2 mt-5">
    <div class="card">
        <div class="card-body">
            <form action="{{route('savePermission')}}" method='post'>
                @csrf
                <div class="mb-3">
                    <label for="permission_name" class="form-label">Permission Name</label>
                    <input
                    type="text"
                    class="form-control"
                    id="permission_name"
                     value="{{isset($permission)?optional($permission)->name:old('name')}}"
                      name="name"
                      >
                    @error('name')
                    <div style="color: red;">{{ $message }}</div>
                    @enderror

                </div>
                <input type="hidden" value="{{optional($permission)->id}}" name="id">
                <div class="mb-3 text-center">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

