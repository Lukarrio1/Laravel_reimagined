<div class="col-sm-8 offset-sm-2 mt-5">
    <div class="card">
        <div class="card-body">
            <form action="{{route('saveRole')}}" method='post'>
                @csrf
                <div class="mb-3">
                    <label for="role_name" class="form-label">Role Name</label>
                    <input type="text" class="form-control" id="role_name" aria-describedby="emailHelp" value="{{isset($role)?optional($role)->name:old('name')}}" name="name">
                    @error('name')
                    <div style="color: red;">{{ $message }}</div>
                    @enderror

                </div>
                <input type="hidden" value="{{optional($role)->id}}" name="id">
                <div class="mb-3 text-center">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
